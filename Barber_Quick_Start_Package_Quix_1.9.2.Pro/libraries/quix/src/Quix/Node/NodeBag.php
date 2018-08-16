<?php

namespace ThemeXpert\Quix\Node;

use ThemeXpert\Config\ConfigBag;
use ThemeXpert\Config\Exceptions\DirectoryNotFoundException;

class NodeBag
{
    /**
     * Instance of config bag.
     *
     * @var \ThemeXpert\Config\ConfigBag
     */
    protected $configBag;

    /**
     * Store path bags.
     *
     * @var array
     */
    protected $pathBags = [];

    /**
     * Create a new instance of node bag.
     *
     * @param $validator
     * @param $transformer
     * @param $finalTransformer
     */
    public function __construct($validator, $transformer, $finalTransformer)
    {
        $this->configBag = new ConfigBag($validator, $transformer, $finalTransformer);
    }

    /**
     * Fill bag to the path bags.
     *
     * @param       $path
     * @param       $url
     * @param array $groups
     */
    public function fill($path, $url, $groups = [])
    {
        $this->pathBags[] = compact("path", "url", "groups");
    }

    /**
     * Adding all path bags to the config bag.
     *
     * @return $this
     */
    public function load()
    {
        foreach ($this->pathBags as $bag) {
            call_user_func_array([$this, 'add'], $bag);
        }

        return $this;
    }

    /**
     * Adding path bag to the config bag.
     *
     * @param       $path
     * @param       $url
     * @param array $group
     *
     * @return $this
     */
    public function add($path, $url, $group = [])
    {
        try {
            $this->configBag->fill($path, $url, $group);
        } catch (DirectoryNotFoundException $e) {
            xception($e->getMessage());
        }

        return $this;
    }

    /**
     * Get config bag.
     *
     * @return ConfigBag
     */
    public function getConfigBag()
    {
        return $this->configBag;
    }
}
