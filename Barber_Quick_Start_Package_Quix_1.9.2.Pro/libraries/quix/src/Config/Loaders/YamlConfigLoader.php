<?php

namespace ThemeXpert\Config\Loaders;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Loader\FileLoader;

class YamlConfigLoader extends FileLoader
{
    /**
     * Load all YML configuration files.
     *
     * @param mixed $resource
     * @param null  $type
     *
     * @return array
     */
    public function load($resource, $type = null)
    {
        $files = $this->getLocator()->locate($resource, null, false);

        $elements = [];

        foreach ($files as $file) {
            # Parse YML file.
            $config = Yaml::parse(file_get_contents($file));

            $elements[$file] = $config;
        }

        return $elements;
    }

    /**
     * Determines the configuration file is YML or not.
     *
     * @param mixed $resource
     * @param null  $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}
