<?php

namespace ThemeXpert\Config\Loaders;

use Symfony\Component\Config\Loader\FileLoader;
use ThemeXpert\Config\Exceptions\ConfigFileIsNotAnArray;

class PhpConfigLoader extends FileLoader
{
    /**
     * Load configuration files.
     *
     * @param mixed $resource
     * @param null  $type
     *
     * @return array
     * @throws ConfigFileIsNotAnArray
     */
    public function load($resource, $type = null)
    {
        $files = $this->getLocator()->locate($resource, null, false);

        $elements = [];
        foreach ($files as $file) {

            $config = require($file);

            if (!is_array($config)) {
                throw new ConfigFileIsNotAnArray("Config file {$file} is not an array!");
            } else {
                $elements[$file] = $config;
            }
        }

        return $elements;
    }

    /**
     *  Determines the configuration file is PHP or not.
     *
     * @param mixed $resource
     * @param null  $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}
