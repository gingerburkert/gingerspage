<?php

namespace ThemeXpert\Config;

use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Config\FileLocator;
use ThemeXpert\Config\Loaders\PhpConfigLoader;
use ThemeXpert\Config\Loaders\YamlConfigLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use ThemeXpert\Config\Exceptions\ConfigFileIsNotAnArray;

class Loader
{
    /**
     * Instance of DelegatingLoader.
     *
     * @var \Symfony\Component\Config\Loader\DelegatingLoader
     */
    protected $loader;

    /**
     * Store path.
     *
     * @var mixed
     */
    protected $path;

    /**
     * Store url.
     *
     * @var mixed
     */
    protected $url;

    /**
     * Create a new instance of loader.
     *
     * @param $path
     * @param $url
     */
    public function __construct($path, $url)
    {
        $this->path = $path;

        $this->url = $url;

        $directories = $this->directories($path);

        $locator = new FileLocator($directories);

        $yamlConfigLoader = new YamlConfigLoader($locator);

        $phpConfigLoader = new PhpConfigLoader($locator);

        $resolver = new LoaderResolver([$yamlConfigLoader, $phpConfigLoader]);

        $this->loader = new DelegatingLoader($resolver);
    }

    /**
     * Load elements.
     *
     * @return array
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    public function load()
    {
        $elements = [];

        # load configuration that has PHP extension.
        try {
            $configs = $this->loader->load('config.php', 'php');

            $elements = array_merge($elements, $configs);
        } catch (ConfigFileIsNotAnArray $e) {
            xception($e->getMessage());
        } catch (InvalidArgumentException $e) {
            //xception( $e->getMessage(), 0);
        }

        # load configuration that has YML extension.
        try {
            $configs = $this->loader->load('config.yml', 'yml');
            $elements = array_merge($elements, $configs);
        } catch (ConfigFileIsNotAnArray $e) {
            xception($e->getMessage());
        } catch (InvalidArgumentException $e) {
            //xception( $e->getMessage(), 0);
        }

        $elements = $this->transform($elements);

        return $elements;
    }

    /**
     * Get all directories of the given path.
     *
     * @param $path
     *
     * @return array
     */
    protected function directories($path)
    {
        $fs = new Filesystem();
        $dirs = $fs->directories($path);
        $dirs = array_merge(array_flatten(array_map(function ($dir) use ($fs) {
            return $fs->directories($dir);
        }, $dirs)), $dirs);

        return $dirs;
    }

    /**
     * Transform elements.
     *
     * @param $elements
     *
     * @return array
     */
    protected function transform($elements)
    {
        $elements = array_map(function ($element, $file) {
            $element['file'] = $file;

            $element['path'] = dirname($file);

            $element['url'] = $this->url . str_replace($this->path, "", dirname($file));

            return $element;
        }, $elements, array_keys($elements));

        return $elements;
    }
}
