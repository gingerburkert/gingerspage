<?php

namespace ThemeXpert\Quix;

use Mobile_Detect;
use Pimple\Container;
use ThemeXpert\View\View;
use Symfony\Component\Yaml\Yaml;
use ThemeXpert\Quix\Node\NodeBag;
use ThemeXpert\Quix\Library\Library;
use ThemeXpert\FormEngine\FormEngine;
use ThemeXpert\View\Engines\PhpEngine;
use ThemeXpert\Config\FinalTransformer;
use ThemeXpert\Quix\Element\ElementBag;
use ThemeXpert\Quix\Renderers\NodeRenderer;
use ThemeXpert\Quix\Renderers\StyleRenderer;
use ThemeXpert\Quix\Renderers\WebFontsRenderer;
use ThemeXpert\FormEngine\ControlsTransformer;
use ThemeXpert\Quix\Node\Config\Validator as NodeValidator;
use ThemeXpert\Quix\Node\Config\Transformer as NodeTransformer;
use ThemeXpert\Quix\Element\Config\Validator as ElementValidator;
use ThemeXpert\Quix\Element\Config\Transformer as ElementTransformer;

class Application
{
    /**
     * Store all nodes.
     */
    protected $allNodes;

    /**
     * Instance of pimple container.
     *
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Instance of cache.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Cache life time.
     *
     * @var int
     */
    protected $cacheLifeTime;

    /**
     * Disabled elements.
     *
     * @var array
     */
    protected $disabledElements = [];

    /**
     * Application constructor.
     *
     * @param Container $container
     * @param Cache     $cache
     */
    public function __construct(Container $container, Cache $cache)
    {
        $this->container = $container;

        $this->cache = $cache;

        $this->initContainer();
    }

    /**
     * Initialize container.
     */
    public function initContainer()
    {
        # Bind final config transformer with the container
        $this->container['finalConfigTransformer'] = function ($container) {
            return new FinalTransformer();
        };

        # Bind elements with the container
        $this->container['elements'] = function ($container) {
            $validator = new ElementValidator();
            $transformer = new ElementTransformer(new FormEngine(new ControlsTransformer()));
            $form = Yaml::parse(file_get_contents(QUIX_PATH . "/app/config/append.yml"));
            $transformer->setAppendForm($form);

            return new ElementBag($validator, $transformer, $container['finalConfigTransformer']);
        };

        # Bind nodes with the container
        $this->container['nodes'] = function ($container) {
            $validator = new NodeValidator();
            $transformer = new NodeTransformer(new FormEngine(new ControlsTransformer()));
            $form = Yaml::parse(file_get_contents(QUIX_PATH . "/app/config/append.yml"));
            $transformer->setAppendForm($form);

            return new NodeBag($validator, $transformer, $container['finalConfigTransformer']);
        };

        # Bind presets with the container
        $this->container['presets'] = function ($container) {
            return new Library();
        };

        # Bind allNodes with the container
        $this->container['allNodes'] = function ($container) {
            $elements = $this->getElements();
            $nodes = $this->getNodes();

            return array_merge($elements, $nodes);
        };

        # Bind mobile detect with the container
        $this->container['mobile_detect'] = function ($container) {
            return new Mobile_Detect();
        };

        # Bind view with the container
        $this->container['view'] = function ($container) {
            return new View(new PhpEngine());
        };

        # Bind view renderer with the container
        $this->container['viewRenderer'] = function ($container) {
            return new NodeRenderer($container['view'], $container['mobile_detect'], $container['allNodes']);
        };

        # Bind style renderer with the container
        $this->container['styleRenderer'] = function ($container) {
            return new StyleRenderer($container['view'], $container['mobile_detect'], $container['allNodes']);
        };

        # Bind web font renderer with the container
        $this->container['webFontsRenderer'] = function ($container) {
            return new WebFontsRenderer($this->cache, $this->getAllNodes());
        };
    }

    /**
     * Get element bag.
     *
     * @return mixed
     */
    public function getElementsBag()
    {
        return $this->container['elements'];
    }

    /**
     * Get nodes bag.
     *
     * @return mixed
     */
    public function getNodesBag()
    {
        return $this->container['nodes'];
    }

    /**
     * Get presents bag.
     *
     * @return mixed
     */
    public function getPresetsBag()
    {
        return $this->container['presets'];
    }

    /**
     * Get all nodes.
     *
     * @return mixed
     */
    public function getAllNodes()
    {
        return $this->container['allNodes'];
    }

    /**
     * Get view renderer.
     *
     * @return mixed
     */
    public function getViewRenderer()
    {
        return $this->container['viewRenderer'];
    }

    /**
     * Get style renderer.
     *
     * @return mixed
     */
    public function getStyleRenderer()
    {
        return $this->container['styleRenderer'];
    }

    /**
     * Get web fonts renderer.
     *
     * @return mixed
     */
    public function getWebFontsRenderer()
    {
        return $this->container['webFontsRenderer'];
    }

    /**
     * Get nodes.
     *
     * @return mixed
     */
    public function getNodes()
    {
        return $this->cache->fetch('nodes', function () {
            return $this->getNodesBag()->load()->getConfigBag()->getConfigs();
        });
    }

    /**
     * Get elements.
     *
     * [IMPORTANT] This method need to decouple. So that, the application can work with other platform.
     *
     * @return mixed
     */
    public function getElements()
    {
        return $this->cache->fetch('elements', function () {
            $elements = $this->getElementsBag()->load()->getConfigBag()->getConfigs();

            $elementsInfo = qxGetElementsInfo();


            $elementsInfo = array_reduce($elementsInfo, function ($carry, $element) {
                $carry[$element->alias] = (array)$element; // must be send as alias

                return $carry;
            }, []);

            $elements = array_map(function ($element) use ($elementsInfo) {
                if (array_key_exists($element['slug'], $elementsInfo)) {
                    $checked = $elementsInfo[$element['slug']]['status'];
                } else {
                    if (in_array($element['slug'], $this->disabledElements)) {
                        $checked = false;
                    } else {
                        $checked = true;
                    }
                }

                $element['enabled'] = $checked;

                return $element;
            }, $elements);

            return $elements;
        });
    }

    /**
     * Get presets.
     *
     * @return mixed
     */
    public function getPresets()
    {
        return $this->cache->fetch('presets', function () {
            return $this->getPresetsBag()->all();
        });
    }

    /**
     * Get cache.
     *
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Disable elements.
     *
     * @param $elements
     */
    public function disableElements($elements)
    {
        $this->disabledElements = array_unique(array_merge($elements, $this->disabledElements));
    }
}
