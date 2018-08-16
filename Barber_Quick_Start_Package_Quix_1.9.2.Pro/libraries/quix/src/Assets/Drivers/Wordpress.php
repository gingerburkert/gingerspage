<?php

namespace ThemeXpert\Assets\Drivers;

use ThemeXpert\View\View;
use ThemeXpert\Assets\AssetException;
use ThemeXpert\View\Engines\PhpEngine;
use ThemeXpert\Assets\Contract\Drivers\AssetsDriver;

class Wordpress implements AssetsDriver
{
    /**
     * View instance.
     *
     * @var \ThemeXpert\View\View
     */
    protected $view;

    /**
     * CSS file alias name.
     *
     * @var array
     */
    protected $cssHandle = [];

    /**
     * CSS order number.
     *
     * @var array
     */
    protected $cssOrder = [];

    /**
     * CSS version number.
     *
     * @var array
     */
    protected $cssVersion = [];

    /**
     * CSS media.
     *
     * @var array
     */
    protected $cssMedia = [];

    /**
     * Inline CSS data.
     *
     * @var array
     */
    protected $cssData = [];

    /**
     * JS alias name.
     *
     * @var array
     */
    protected $jsHandle = [];

    /**
     * JS order number.
     *
     * @var array
     */
    protected $jsOrder = [];

    /**
     * JS version number.
     *
     * @var array
     */
    protected $jsVersion = [];

    /**
     * JS dependencies.
     *
     * @var array
     */
    protected $jsDependencies = [];

    /**
     * Inline JS data.
     *
     * @var array
     */
    protected $jsData = [];

    /**
     * Loadable JS files.
     *
     * @var array
     */
    protected $loadableJS = [];

    /**
     * Loadable CSS files.
     *
     * @var array
     */
    protected $loadableCSS = [];

    /**
     * create a new instance of joomla.
     */
    public function __construct()
    {
        $this->setViewInstance();
    }

    /**
     * Set view class instance.
     */
    protected function setViewInstance()
    {
        if(is_null($this->view)) {
            $this->view = new View(new PhpEngine);
        }
    }

    /**
     * Added javascript with the application.
     *
     * @param string $handle
     * @param string $src
     * @param array  $data
     * @param array  $dependencies
     * @param null   $order
     * @param null   $version
     *
     * @throws AssetException
     */
    public function Js($handle, $src, $data = [], $dependencies = [], $order = null, $version = null)
    {
        $this->setJSHandle($handle, $src);

        $this->jsData[$handle] = $data;

        $this->jsDependencies[$handle] = $dependencies;

        $this->setJSOrderNumber($handle, $order);

        $this->jsVersion[$handle] = $version;
    }

    /**
     * Added stylesheet with the application.
     *
     * @param string $handle
     * @param string $src
     * @param array  $data
     * @param array  $dependencies
     * @param null   $order
     * @param null   $version
     * @param string $media
     *
     * @throws AssetException
     */
    public function Css($handle, $src, $data = [], $dependencies = [], $order = null, $version = null, $media = 'all')
    {
        $this->setCssHandle($handle, $src);

        $this->cssData[$handle] = $data;

        $this->cssDependencies[$handle] = $dependencies;

        $this->setCSSOrderNumber($handle, $order);

        $this->cssVersion[$handle] = $version;

        $this->cssMedia[$handle] = $media;
    }

    /**
     * Load all css and js files.
     */
    public function load()
    {
        $this->loadCSS();

        $this->loadJS();
    }

    /**
     * Load all registered inline and external CSS files.
     */
    protected function loadCSS()
    {
        $sortedCSS = $this->getSortedCSS();

        $this->resolveLoadableCSS($sortedCSS);

        $this->addCSSWithJFactory();
    }

    /**
     * Load all registered inline and external JS files.
     */
    protected function loadJS()
    {
        $sortedJS = $this->getSortedJS();

        $this->resolveLoadableJS($sortedJS);

        $this->addJSWithJFactory();
    }

    /**
     * Resolved all required dependencies for the JS handle.
     *
     * @param string $handle
     */
    protected function resolvedJSDependencies($handle)
    {
        foreach($this->jsDependencies[$handle] as $source) {
            if(! in_array($source, $this->loadableJS)) {
                if(! empty($this->jsDependencies[$source])) {
                    $this->resolvedJSDependencies($source);
                } else {
                    $this->setLoadableJS($source);
                }
            }
        }

        $this->setLoadableJS($handle);
    }

    /**
     * Resolved all required dependencies for the CSS handle.
     *
     * @param string $handle
     */
    protected function resolvedCSSDependencies($handle)
    {
        foreach($this->cssDependencies[$handle] as $source) {
            if(! in_array($source, $this->loadableCSS)) {
                if(! empty($this->cssDependencies[$source])) {
                    $this->resolvedCSSDependencies($source);
                } else {
                    $this->setLoadableCSS($source);
                }
            }
        }

        $this->setLoadableCSS($handle);
    }

    /**
     * Get sorted JS.
     *
     * @return array
     */
    protected function getSortedJS()
    {
        $jsOrder = $this->jsOrder;
        ksort($jsOrder);

        return $jsOrder;
    }

    /**
     * Get sorted CSS.
     *
     * @return array
     */
    protected function getSortedCSS()
    {
        $cssOrder = $this->cssOrder;
        ksort($cssOrder);

        return $cssOrder;
    }

    /**
     * Resolved loadable js.
     *
     * If any dependencies need for the given JS file,
     * then resolved this JS first. After that, set the JS to the loadableJS variable.
     *
     * @param array $sortedJS
     */
    protected function resolveLoadableJS($sortedJS)
    {
        foreach ($sortedJS as $handle) {
            if (!empty($this->jsDependencies[$handle])) {
                $this->resolvedJSDependencies($handle);
            } else {
                $this->setLoadableJS($handle);
            }
        }
    }

    /**
     * Resolved loadable css.
     *
     * If any dependencies need for the given CSS file,
     * then resolved this CSS first. After that, set the CSS to the loadableCSS variable.
     *
     * @param array $sortedCSS
     */
    protected function resolveLoadableCSS($sortedCSS)
    {
        foreach ($sortedCSS as $handle) {
            if (!empty($this->cssDependencies[$handle])) {
                $this->resolvedCSSDependencies($handle);
            } else {
                $this->setLoadableCSS($handle);
            }
        }
    }

    /**
     * Add external and inline JS with the JFactory.
     * The addJSWithJFactory decides the inline or external JS file by the source JS file extension.
     * If source JS file contains .php extension then this method treat the JS file as inline JS file.
     * If source JS file contains .js extension then this method treat the JS file as external JS file.
     *
     * @throws AssetException
     */
    protected function addJSWithJFactory()
    {
        foreach ($this->loadableJS as $handle) {

            if(empty($this->jsHandle[$handle])) {
                throw new AssetException("The handle name [ $handle ] does not found.");
            }

            $path = $this->jsHandle[$handle];
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'js') {
                $version = $this->getJSVersion($handle);

//                $this->jFactory->addCustomTag("<script src='" . $path . $version ."'></script>");
                echo "<script src='" . $path . $version ."'></script>";
            }

            if ($extension === 'php') {
                $content = $this->view->make($path, $this->jsData[$handle]);

//                $this->jFactory->addCustomTag("<script>" . $content . "</script>");
                echo "<script>" . $content . "</script>";
            }
        }
    }

    /**
     * Add external and inline CSS with the JFactory.
     * The addJSWithJFactory decides the inline or external CSS file by the source CSS file extension.
     * If source CSS file contains .php extension then this method treat the CSS file as inline CSS file.
     * If source CSS file contains .css extension then this method treat the CSS file as external CSS file.
     *
     * @throws AssetException
     */
    protected function addCSSWithJFactory()
    {
        foreach ($this->loadableCSS as $handle) {

            if(empty($this->cssHandle[$handle])) {
                throw new AssetException("The handle name [ $handle ] does not found.");
            }

            $path = $this->cssHandle[$handle];
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'css') {
                $version = $this->getCSSVersion($handle);

                $media = $this->cssMedia[$handle];

//                $this->jFactory->addCustomTag("<link rel='stylesheet' href='{$path}{$version}' media='{$media}'/>");
                echo "<link rel='stylesheet' href='{$path}{$version}' media='{$media}'/>";
            }

            if ($extension === 'php') {
                $content = $this->view->make($path, $this->cssData[$handle]);

//                $this->jFactory->addCustomTag("<style>" . $content . "</style>");
                echo "<style>" . $content . "</style>";
            }
        }
    }

    /**
     * Set loadable JS.
     *
     * The JS file saved as loadable JS in the loadableJS variable.
     *
     * @param $handle
     */
    protected function setLoadableJS($handle)
    {
        if (!in_array($handle, $this->loadableJS)) {
            $this->loadableJS[] = $handle;
        }
    }

    /**
     * Set loadable CSS.
     *
     * The CSS file saved as loadable CSS in the loadableCSS variable.
     *
     * @param $handle
     */
    protected function setLoadableCSS($handle)
    {
        if (!in_array($handle, $this->loadableCSS)) {
            $this->loadableCSS[] = $handle;
        }
    }

    /**
     * Set JS order number.
     *
     * @param $handle
     * @param $order
     *
     * @throws AssetException
     */
    protected function setJSOrderNumber($handle, $order)
    {
        if (!empty($order) and is_int($order)) {
            $this->jsOrder[$order] = $handle;
        } else if (empty($order)) {
            $ran = rand(10000, 100000);
            $this->jsOrder[$ran] = $handle;
        }  else {
            throw new AssetException("JS [ {$handle} ] Order number must be integer.");
        }
    }

    /**
     * Set CSS order number.
     *
     * @param $handle
     * @param $order
     *
     * @throws AssetException
     */
    protected function setCssOrderNumber($handle, $order)
    {
        if (!empty($order) and is_int($order)) {
            $this->cssOrder[$order] = $handle;
        } else if (empty($order)) {
            $ran = rand(10000, 100000);
            $this->cssOrder[$ran] = $handle;
        }  else {
            throw new AssetException("CSS [ {$handle} ] Order number must be integer.");
        }
    }

    /**
     * Get JS version number.
     *
     * @param $handle
     *
     * @return null|string
     * @throws AssetException
     */
    protected function getJSVersion($handle)
    {
        $version = '?v=';

        if(!empty($this->jsVersion[$handle]) and is_int($this->jsVersion[$handle])) {
            $version .= $this->jsVersion[$handle];

            return $version;
        }

        if(empty($this->jsVersion[$handle])) {
            return null;
        }

        throw new AssetException("Version number must be integer for the JS handle [ $handle ].");
    }

    /**
     * Get CSS version number.
     *
     * @param $handle
     *
     * @return null|string
     * @throws AssetException
     */
    protected function getCSSVersion($handle)
    {
        $version = '?v=';

        if(!empty($this->cssVersion[$handle]) and is_int($this->cssVersion[$handle])) {
            $version .= $this->cssVersion[$handle];

            return $version;
        }

        if(empty($this->cssVersion[$handle])) {
            return null;
        }

        throw new AssetException("Version number must be integer for the CSS handle [ $handle ].");
    }

    /**
     * Set CSS handle name and source.
     *
     * @param $handle
     * @param $src
     */
    protected function setCssHandle($handle, $src)
    {
        $this->cssHandle[$handle] = $src;
    }

    /**
     * Set JS handle name and source.
     *
     * @param $handle
     * @param $src
     */
    protected function setJSHandle($handle, $src)
    {
        $this->jsHandle[$handle] = $src;
    }
}