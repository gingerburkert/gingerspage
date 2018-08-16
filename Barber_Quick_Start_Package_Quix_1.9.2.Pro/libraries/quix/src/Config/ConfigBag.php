<?php

namespace ThemeXpert\Config;

use ThemeXpert\Config\Contracts\ValidatorInterface;
use ThemeXpert\Config\Contracts\TransformerInterface;
use ThemeXpert\Config\Exceptions\DirectoryNotFoundException;

class ConfigBag
{
    /**
     * Store configurations.
     *
     * @var array
     */
    protected $configs = [];

    /**
     * Instance of transformer.
     *
     * @var TransformerInterface
     */
    protected $transformer;

    /**
     * Instance of validator.
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Final transformer.
     *
     * @var FinalTransformer
     */
    protected $finalTransformer;

    /**
     * Create a new instance of config bag.
     *
     * @param ValidatorInterface   $validator
     * @param TransformerInterface $transformer
     * @param FinalTransformer     $finalTransformer
     */
    public function __construct(ValidatorInterface $validator, TransformerInterface $transformer, FinalTransformer $finalTransformer)
    {
        $this->validator = $validator;

        $this->transformer = $transformer;

        $this->finalTransformer = $finalTransformer;
    }

    /**
     * Fill up the configuration array.
     *
     * @param $path
     * @param $url
     * @param $groups
     *
     * @return $this
     * @throws DirectoryNotFoundException
     */
    public function fill($path, $url, $groups)
    {
        if (!is_dir($path)) {
            throw new DirectoryNotFoundException("directory `{$path}` was not found in filesystem");
        }

        $configs = $this->load($path, $url);

        $this->validate($configs);

        $configs = $this->transform($configs, $path);

        $configs = $this->setGroups($configs, $groups);

        $this->configs = array_merge($this->configs, $configs);

        return $this;
    }

    /**
     * Get configuration.
     *
     * @return array
     */
    public function getConfigs()
    {
        $configs = $this->finalTransformer->transform($this->configs);

        return $configs;
    }

    /**
     * Load elements.
     *
     * @param $path
     * @param $url
     *
     * @return array
     */
    protected function load($path, $url)
    {
        $loader = new Loader($path, $url);

        return $loader->load();
    }

    /**
     * Validating the configuration file.
     *
     * @param $configs
     *
     * @return mixed
     */
    protected function validate($configs)
    {
        $configs = array_reduce($configs, function ($carry, $config) {
            $this->validator->validate($config, $config['file']);

            return $carry;
        }, []);

        return $configs;
    }

    /**
     * Transform the configuration file.
     *
     * @param $configs
     * @param $path
     *
     * @return array
     */
    protected function transform($configs, $path)
    {
        $configs = array_map(function ($config) use($path) {
            return $this->transformer->transform($config, $path);
        }, $configs);

        return $configs;
    }

    /**
     * Set groups.
     *
     * @param $configs
     * @param $groups
     *
     * @return array
     */
    protected function setGroups($configs, $groups)
    {
        $configs = array_map(function ($config) use ($groups) {
            $config['groups'] = array_merge(isset($config['groups']) ? $config['groups'] : [], (array)$groups);

            return $config;
        }, $configs);

        return $configs;
    }
}
