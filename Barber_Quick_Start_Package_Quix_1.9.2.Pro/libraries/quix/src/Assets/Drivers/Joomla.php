<?php

namespace ThemeXpert\Assets\Drivers;

use JHtml;
use JFactory;
use Mobile_Detect;
use JComponentHelper;
use ThemeXpert\View\View;
use MatthiasMullie\Minify\JS;
use MatthiasMullie\Minify\CSS;
use ThemeXpert\Assets\AssetException;
use ThemeXpert\View\Engines\PhpEngine;
use ThemeXpert\Assets\Concerns\Filesystem;
use ThemeXpert\Assets\Concerns\HttpRequest;
use ThemeXpert\Assets\Contract\Drivers\AssetsDriver;

class Joomla implements AssetsDriver
{
    use Filesystem, HttpRequest;

    /**
     * View instance.
     *
     * @var \ThemeXpert\View\View
     */
    protected $view;

    /**
     * JFactory instance.
     *
     * @var \JFactory
     */
    protected $jFactory;

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
     * Instance of css minifier lib.
     *
     * @var \MatthiasMullie\Minify\CSS
     */
    protected $cssMinifier;

    /**
     * Instance of js minifier lib.
     *
     * @var \MatthiasMullie\Minify\CSS
     */
    protected $jsMinifier;

    /**
     * Minified JS file path.
     *
     * @var string
     */
    protected $jsPath;

    /**
     * Minified CSS file path.
     *
     * @var string
     */
    protected $cssPath;

    /**
     * Bulk css rules.
     *
     * @var string
     */
    protected $bulkCssRules;

    /**
     * Quix view type.
     *
     * @var string
     */
    protected $viewType;

    /**
     * Quix page id.
     *
     * @var string
     */
    protected $pageId;

    /**
     * Asset parent details.
     *
     * @var array
     */
    protected $parent;

    /**
     * Current device type
     *
     * @var string
     */
    protected $device;

    /**
     * An array of the desktop version CSS rules.
     * @var string
     * @var array
     */
    protected $cssPropForDesktop = [];

    /**
     * An array of the tablet version CSS rules.
     *
     * @var array
     */
    protected $cssPropForTablet = [];


    /**
     * An array of the phone version CSS rules.
     *
     * @var array
     */

    protected $cssPropForPhone = [];
    protected $loaded = false;


    /**
     * create a new instance of joomla.
     */
    public function __construct()
    {
        $this->jFactory =  JFactory::getDocument();

        $this->cssMinifier = new CSS();

        $this->jsMinifier = new JS();

        $this->viewType = JFactory::getApplication()->input->get('view', 'page');

        $this->pageId = JFactory::getApplication()->input->get('id');

        $this->setViewInstance();

        $Mobile_Detect = new Mobile_Detect();
        if($Mobile_Detect->isMobile()){
            $device = 'mobile';
        }elseif($Mobile_Detect->isTablet()){
            $device = 'tablet';
        }else{
            $device = 'all';
        }

        $this->device = $device;
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
     * Set desktop version CSS rules.
     *
     * @param string $selector
     * @param string $rules
     * @param array $data
     */
    public function cssForDesktop($selector, $declaration, $data = [])
    {
        if( !empty($declaration) ){
            if(!isset($this->cssPropForDesktop[$selector])) $this->cssPropForDesktop[$selector] = '';
            if($this->rulesIsFilePath($declaration)) {
                $content = $this->view->make($declaration, $data);

                $this->cssPropForDesktop[$selector] .= $content;
            } else {
                $this->cssPropForDesktop[$selector] .= $declaration;
            }
        }
    }

    /**
     * Set tablet version CSS rules.
     *
     * @param string $selector
     * @param string $declaration
     * @param array $data
     */
    public function cssForTablet($selector, $declaration, $data = [])
    {
        if( !empty($declaration) ){
            if(!isset($this->cssPropForTablet[$selector])) $this->cssPropForTablet[$selector] = '';

            if($this->rulesIsFilePath($declaration)) {
                $content = $this->view->make($declaration, $data);

                $this->cssPropForTablet[$selector] .= $content;
            } else {
                $this->cssPropForTablet[$selector] .= $declaration;
            }
        }
    }

    /**
     * Set phone version CSS rules.
     *
     * @param string $selector
     * @param string $declaration
     * @param array $data
     */
    public function cssForPhone($selector, $declaration, $data = [])
    {
        if( !empty($declaration) ){
            if(!isset($this->cssPropForPhone[$selector])) $this->cssPropForPhone[$selector] = '';

            if($this->rulesIsFilePath($declaration)) {
                $content = $this->view->make($declaration, $data);

                $this->cssPropForPhone[$selector] .= $content;
            } else {
                $this->cssPropForPhone[$selector] .= $declaration;
            }
        }
    }

    /**
     * Determine the given declaration is file path or CSS rules.
     *
     * @param string $declaration
     * @return bool
     */
    protected function rulesIsFilePath($declaration)
    {
        return file_exists($declaration);
    }


    /**
     * Load all css and js files.
     */
    public function load()
    {
        $this->loadCSS();

        $this->loadJS();

        $this->resetObject();
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
        $config = JComponentHelper::getComponent('com_quix')->params;
        if($this->isPreviewMode() or $this->isEditMode() or $config->get('dev_mode', 0)) {

            $this->makeWithoutMinifiedJs();

        } else {
            // load all js files
            $this->loadExternalJs();

            $jsFileNumber = 1;

            // now load elements js inlines
            foreach($this->bulkCssRules as $modifiedDate => $rules) {
                if($jsFileNumber++ == 1) {
                    $minifiedJsPath = $this->getMinifiedJsFilePath(true, $modifiedDate);

                    if ($this->fileExists($this->jsPath)) {
                        $this->jFactory->addScript($minifiedJsPath);
                    } else {
                        $this->makeMinifiedJs($minifiedJsPath, $modifiedDate);
                    }
                }
            }
        }
    }

    /**
     * Load only external js files
     *
     * @throws AssetException
     */
    protected function loadExternalJs()
    {
        foreach ($this->loadableJS as $handle) {

            if(empty($this->jsHandle[$handle])) {
                throw new AssetException("The handle name [ $handle ] does not found.");
            }

            $path = $this->jsHandle[$handle];

            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'js') {

                $this->jFactory->addScript($path);
            }
        }
    }

    /**
     * Make minified js file for the given path.
     *
     * @param string $minifiedJsPath
     *
     * @param string $modifiedDate
     *
     * @throws AssetException
     */
    protected function makeMinifiedJs($minifiedJsPath, $modifiedDate)
    {
        // remove any previous file for this page
        foreach (glob(JPATH_SITE . "/media/quix/js/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-{$this->device}.js") as $filename) {
            unlink($filename);
        }

        foreach ($this->loadableJS as $handle) {

            if(empty($this->jsHandle[$handle])) {
                throw new AssetException("The handle name [ $handle ] does not found.");
            }

            $path = $this->jsHandle[$handle];

            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'js') {

                # uncomment this if need to minify all js files
                // $version = $this->getJSVersion($handle);
                // $old = $this->getContents($this->jsPath);
                // $this->putContents($this->jsPath, $old . (!empty($old) ? ";" : "") . $this->getContents(PATH_ROOT . $path));

                $this->jFactory->addScript($path);
            }

            if ($extension === 'php') {
                $content = $this->view->make($path, $this->jsData[$handle]);

                # uncomment this line when you think quix needs js minification
                //$this->jsMinifier->add($content);

                $old = $this->getContents($this->jsPath);

                # uncomment this line when you think quix needs js minification
                //$this->putContents($this->jsPath, $old . ";" . $this->jsMinifier->minify() . ";");

                $this->putContents($this->jsPath, $old . (!empty($old) ? ";" : "") . $content);

            }
        }

        if ($this->fileExists($this->jsPath)) {
            $this->jFactory->addScript($minifiedJsPath);
        }
    }

    /**
     * Make unminified js file for the given path.
     *
     * @throws AssetException
     */
    protected function makeWithoutMinifiedJs()
    {
        foreach ($this->loadableJS as $handle) {

            if(empty($this->jsHandle[$handle])) {
                throw new AssetException("The handle name [ $handle ] does not found.");
            }

            $path = $this->jsHandle[$handle];

            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'js') {
                $this->jFactory->addScript($path);
            }

            if ($extension === 'php') {
                $content = $this->view->make($path, $this->jsData[$handle]);

                $this->jFactory->addScriptDeclaration($content);
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
        $config = JComponentHelper::getComponent('com_quix')->params;
        if($this->isPreviewMode() or $this->isEditMode() or $config->get('dev_mode', 0)) {

            $this->makeWithoutMinifiedCss();

        } else {
            // load all js files
            $this->loadExternalCss();

            $cssFileNumber = 1;

            // now load editors css
            foreach($this->bulkCssRules as $modifiedDate => $rules) {
                $minifiedCssPath = $this->getMinifiedCssFilePath(true, $modifiedDate);

                if ($this->fileExists($this->cssPath)) {
                    $this->jFactory->addStyleSheet($minifiedCssPath);
                } else {
                    $this->makeMinifiedCss($minifiedCssPath, $modifiedDate, $cssFileNumber++);
                }
            }
        }
    }

    /**
     * Getting responsive CSS rules.
     */
    protected function getResponsiveCssRules()
    {
        $desktopCss = '';
        $tabletCss = '';
        $phoneCss = '';

        // adding desktop version css rules to the joomla style declaration
        foreach($this->cssPropForDesktop as $quixNodeSelector => $rules) {
            $desktopCss .= "$quixNodeSelector { $rules } ";
        }

        // adding tablet version css rules to the joomla style declaration
        $tabletMediaQueryStart = "@media screen and (min-width: 768px) and (max-width: 992px){ ";

        foreach($this->cssPropForTablet as $quixNodeSelector => $rules) {
            $tabletCss .= "$quixNodeSelector { $rules } ";
        }

        $tabletMediaQueryEnd = " } ";
        
        $tabletCss = $tabletMediaQueryStart . $tabletCss . $tabletMediaQueryEnd ;

        // adding phone version css rules to the joomla style declaration
        $phoneMediaQueryStart = "@media screen and (max-width: 767px) { ";

        foreach($this->cssPropForPhone as $quixNodeSelector => $rules) {
            $phoneCss .= "$quixNodeSelector { $rules } ";
        }

        $phoneMediaQueryEnd = " } ";
        
        $phoneCss = $phoneMediaQueryStart . $phoneCss . $phoneMediaQueryEnd;

        return $desktopCss . $tabletCss . $phoneCss;
    }

    /**
     * Load external css files
     *
     * @throws AssetException
     */
    protected function loadExternalCss()
    {
        foreach ($this->loadableCSS as $handle) {

            if(empty($this->cssHandle[$handle])) {
                throw new AssetException("The handle name [ $handle ] does not found.");
            }

            $path = $this->cssHandle[$handle];

            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'css') {
                 $this->jFactory->addStyleSheet($path);
            }
        }
    }

    /**
     * Make minified css file for the given path.
     *
     * @param string $minifiedCssPath
     *
     * @param string $modifiedDate
     *
     * @param int    $cssFileNumber
     *
     * @throws AssetException
     */
    protected function makeMinifiedCss($minifiedCssPath, $modifiedDate, $cssFileNumber)
    {
        // remove any previous file for this page
        foreach (glob(JPATH_SITE . "/media/quix/css/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-{$this->device}.css") as $filename) {
            unlink($filename);
        }

        if($cssFileNumber == 1) {
            foreach ($this->loadableCSS as $handle) {

                if(empty($this->cssHandle[$handle])) {
                    throw new AssetException("The handle name [ $handle ] does not found.");
                }

                $path = $this->cssHandle[$handle];

                $extension = pathinfo($path, PATHINFO_EXTENSION);

                if ($extension === 'css') {
                    $this->jFactory->addStyleSheet($path);
                }

                if ($extension === 'php') {
                    $content = $this->view->make($path, $this->cssData[$handle]);

                    $this->cssMinifier->add($content);

                    $old = $this->getContents($this->cssPath);

                    $this->putContents($this->cssPath, $old . $this->cssMinifier->minify());
                }
            }
            
            $this->putContents($this->cssPath, $this->getContents($this->cssPath) . $this->getBulkCssRules($modifiedDate) . $this->getResponsiveCssRules());

            if ($this->fileExists($this->cssPath)) {
                $this->jFactory->addStyleSheet($minifiedCssPath);
            }
        } else {
            $this->putContents($this->cssPath, $this->getBulkCssRules($modifiedDate) . $this->getResponsiveCssRules());

            if ($this->fileExists($this->cssPath)) {
                $this->jFactory->addStyleSheet($minifiedCssPath);
            }
        }
    }

    /**
     * Make unminified css file for the given path.
     *
     * @throws AssetException
     */
    protected function makeWithoutMinifiedCss()
    {
        foreach ($this->loadableCSS as $handle) {

            if(empty($this->cssHandle[$handle])) {
                throw new AssetException("The handle name [ $handle ] does not found.");
            }

            $path = $this->cssHandle[$handle];

            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if ($extension === 'css') {
                $this->jFactory->addStyleSheet($path);
            }

            if ($extension === 'php') {
                $content = $this->view->make($path, $this->cssData[$handle]);

                $this->jFactory->addStyleDeclaration($content);
            }
        }

        if( is_array( $this->bulkCssRules ) ) {
            foreach($this->bulkCssRules as $bulkRule) {
                $this->jFactory->addStyleDeclaration($bulkRule);
            }
        }

        $this->jFactory->addStyleDeclaration($this->getResponsiveCssRules());
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
            // $ran = rand(500, 1000);
            $ran = $this->nextEmptyOrder(count($this->jsOrder));
            $this->jsOrder[$ran] = $handle;
        }  else {
            throw new AssetException("JS [ {$handle} ] Order number must be integer.");
        }
    }

    protected function nextEmptyOrder($ord, $asset = 'js')
    {
        switch ($asset) {
            case 'js':
                if(isset($this->jsOrder[$ord]))
                {
                    $this->nextEmptyOrder($ord+1);
                }

                break;

            case 'css':
            default:
                if(isset($this->cssOrder[$ord]))
                {
                    $this->nextEmptyOrder($ord+1, 'css');
                }
                break;
        }


        return $ord;
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
            // $ran = rand(500, 1000);
            $ran = $this->nextEmptyOrder(count($this->cssOrder), 'css');
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

    /**
     * Bulk css content minifier.
     *
     * @param string $pageModifiedTimeStamp
     * @param string $bulkRules
     * @param string $type
     * @param string $id
     */
    public function bulkCssMinifier( $pageModifiedTimeStamp, $bulkRules , $type, $id)
    {
        $this->bulkCssRules[$pageModifiedTimeStamp] = $bulkRules;

        $this->parent[$pageModifiedTimeStamp] = [
            'type' => $type,
            'id' => $id
        ];
    }

    /**
     * Get minified content of the bulk css rules.
     *
     * @param string $modifiedDate
     *
     * @return string
     */
    protected function getBulkCssRules($modifiedDate)
    {
        $cssMinifier = new CSS();

        $cssMinifier->add($this->bulkCssRules[$modifiedDate]);

        return $cssMinifier->minify();
    }

    /**
     * Get minified js file path.
     *
     * @param bool|boolian $url , if true return with version number
     *
     * @param              $modifiedDate
     *
     * @return string
     */
    protected function getMinifiedJsFilePath($url = false, $modifiedDate)
    {
        if($url)
        {
            $this->jsPath = PATH_ROOT . "/media/quix/js/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.js";

            // return "media/quix/js/page-{$this->pageId}.js?v={$this->getPageVersionNumber()}";
            return "media/quix/js/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.js";
        }
        else
        {
            $this->jsPath = PATH_ROOT . "/media/quix/js/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.js";

            // return "media/quix/js/page-{$this->pageId}.js";
            return "media/quix/js/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.js";
        }
    }

    /**
     * Get minified css file path.
     *
     * @param bool|boolian $url , if true return with version number
     *
     * @param              $modifiedDate
     *
     * @return string
     */
    protected function getMinifiedCssFilePath($url = false, $modifiedDate)
    {
        if($url)
        {
            $this->cssPath = PATH_ROOT . "/media/quix/css/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.css";

            // return "media/quix/css/page-{$this->pageId}.css?v={$this->getPageVersionNumber()}";
            return "media/quix/css/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.css";
        }
        else
        {
            $this->cssPath = PATH_ROOT . "/media/quix/css/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.css";

            // return "media/quix/css/{$this->viewType}-{$this->pageId}.css";
            return "media/quix/css/{$this->parent[$modifiedDate]['type']}{$this->parent[$modifiedDate]['id']}-v{$this->getPageVersionNumber($modifiedDate)}-{$this->device}.css";
        }
    }

    /**
     * Get page version number.
     *
     * @param $modifiedDate
     *
     * @return string
     */
    protected function getPageVersionNumber($modifiedDate)
    {
        return substr(md5($modifiedDate), 0, 8);
    }
    /**
    * Method resetObject
    * @param not required
    * @return nothing
    */
    function resetObject()
    {
        $this->cssHandle = array();
        $this->cssOrder = array();
        $this->cssVersion = array();
        $this->cssMedia = array();
        $this->cssData = array();
        $this->loadableCSS = array();
        $this->bulkCssRules = array();
        $this->cssDependencies = array();
        $this->cssPath = '';

        $this->jsHandle = array();
        $this->jsOrder = array();
        $this->jsVersion = array();
        $this->jsDependencies = array();
        $this->jsData = array();
        $this->loadableJS = array();
        $this->jsPath = '';


        $this->parent = array();
        $this->viewType = '';
        $this->pageId = '';
        $this->cssPropForPhone = [];
        $this->cssPropForTablet = [];
        $this->cssPropForDesktop = [];
    }
}
