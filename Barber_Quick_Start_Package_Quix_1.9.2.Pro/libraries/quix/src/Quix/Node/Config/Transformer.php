<?php

namespace ThemeXpert\Quix\Node\Config;

use ThemeXpert\Config\Contracts\TransformerInterface;
use ThemeXpert\FormEngine\FormEngine;

class Transformer implements TransformerInterface
{
    /**
     * @var \ThemeXpert\FormEngine\FormEngine
     */
    protected $formEngine;

    /**
     * Append form.
     *
     * @var array
     */
    protected $appendForm = [];

    /**
     * Create a new instance of transformer.
     *
     * @param FormEngine $formEngine
     */
    public function __construct(FormEngine $formEngine)
    {
        $this->formEngine = $formEngine;
    }

    /**
     * Transforming.
     *
     * @param $config
     *
     * @return mixed
     */
    public function transform($config, $path = null)
    {
        $config['element_path'] = $path;
        $config['view_file'] = $this->getView($config);
        $config['css_file'] = $this->getCss($config);
        $config['dynamic_style_file'] = $this->getStyle($config);
        $config['form'] = $this->getForm($config);
        $config['visibility'] = $this->getVisibility($config);
        $config['thumb_file'] = $this->getThumbnail($config);

        return $config;
    }

    /**
     * Get form.
     *
     * @param $config
     *
     * @return mixed
     */
    public function getForm($config)
    {
        $form = array_get($config, 'form', []);
        $path = array_get($config, 'element_path', []);

        if(strpos($path, 'libraries/quix/app/elements/') !== false) {
            $path = '/libraries/quix/app/elements/';
        } else {
            $fragments = explode("/", $path);

            if(in_array("templates", $fragments)) {
                $startPushing = false;
                $path = [];

                foreach($fragments as $fragment) {
                    if($fragment == "templates" or $startPushing) {
                        array_push($path, $fragment);
                        $startPushing = true;
                    }
                }

                $path = "/" . implode("/", $path);
            }
        }

        # Local copy
        $appendForm = $this->appendForm;

        # If there is something in the config file then add it to the append file
        foreach ($appendForm as $tab => $controls) {
            if (array_key_exists($tab, $form)) {
                $appendForm[$tab] = array_merge($controls, $form[$tab]);
            }
        }

        # Merge all
        $form = array_merge($form, $appendForm);

        return $this->formEngine->transform($form, $path);
    }

    /**
     * Ge view.
     *
     * @param $config
     *
     * @return string
     */
    protected function getView($config)
    {
        if (array_get($config, 'view')) {
            return $config['path'] . "/" . $config['view'];
        } else {
            return $config['path'] . "/view.php";
        }
    }

    /**
     * Set append form.
     *
     * @param mixed $appendForm
     */
    public function setAppendForm($appendForm)
    {
        $this->appendForm = $appendForm;
    }

    /**
     * Get thumbnail.
     *
     * @param $config
     *
     * @return string
     */
    protected function getThumbnail($config)
    {
        if (array_get($config, 'thumb')) {
            return $config['url'] . "/" . $config['thumb'];
        } else {
            if (file_exists($config['path'] . "/node.svg")) {
                return $config['url'] . "/node.svg";
            } else {
                if (file_exists($config['path'] . "/node.png")) {
                    return $config['url'] . "/node.png";
                } else {
                    return QUIX_DEFAULT_ELEMENT_IMAGE;
                }
            }
        }
    }

    /**
     * Get style.
     *
     * @param $config
     *
     * @return string
     */
    protected function getStyle($config)
    {
        if (array_get($config, 'style')) {
            return $config['path'] . "/" . $config['style'];
        } else {
            return $config['path'] . "/style.php";
        }
    }

    /**
     * Get css.
     *
     * @param $config
     *
     * @return string
     */
    protected function getCss($config)
    {
        if (array_get($config, 'css')) {
            return $config['url'] . "/" . $config['css'];
        } else {
            return $config['url'] . "/node.css";
        }
    }

    /**
     * Get groups.
     *
     * @param $config
     *
     * @return array
     */
    protected function getGroups($config)
    {
        return (array)array_get($config, 'groups', []);
    }

    /**
     * Get visibility.
     *
     * @param $config
     *
     * @return array
     */
    protected function getVisibility($config)
    {
        return ['lg' => true, 'md' => true, 'sm' => true, 'xs' => true];
    }
}
