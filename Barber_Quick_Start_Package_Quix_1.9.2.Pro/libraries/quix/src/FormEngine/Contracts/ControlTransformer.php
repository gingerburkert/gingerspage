<?php

namespace ThemeXpert\FormEngine\Contracts;

abstract class ControlTransformer implements ControlTransformerInterface
{
    /**
     * Transformer.
     *
     * @param $config
     *
     * @return array
     */
    public function transform($config, $path)
    {
        $output = [];

        $output['advanced'] = $this->get($config, 'advanced', false);
        $output['depends'] = $this->getDepends($config);
        $output['default'] = $this->getValue($config);
        $output['reset'] = $this->get($config, 'reset', false);

        return $output;
    }

    /**
     * Get value from the configuration file.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getValue($config)
    {
        return $this->get($config, 'value', "");
    }

    /**
     * Get label from the configuration file.
     *
     * @param      $config
     * @param null $label
     *
     * @return mixed|null
     */
    public function getLabel($config, $label = null)
    {
        if (!$label) {
            $label = ucfirst(str_replace("_", " ", $config['name']));
        }

        return $this->get($config, 'label', $label);
    }

    /**
     * Get placeholder from the configuration file.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getPlaceholder($config)
    {
        return $this->get($config, 'placeholder');
    }

    /**
     * Get help from the configuration file.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getHelp($config)
    {
        return $this->get($config, 'help');
    }

    /**
     * Get class from the configuration file.
     *
     * @param      $config
     * @param null $klass
     *
     * @return string
     */
    public function getClass($config, $klass = null)
    {
        if (!$klass) {
            $klass = "fe-control-" . $this->getType($config) . " fe-control-name-" . $this->getName($config);
        }

        return $klass . " " . $this->get($config, 'class', '');
    }

    /**
     * Get schema from the configuration file.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getSchema($config)
    {
        return $this->get($config, 'schema', []);
    }

    /**
     * Get type from the configuration file.
     *
     * @param        $config
     * @param string $type
     *
     * @return mixed|null
     */
    public function getType($config, $type = "text")
    {
        return $this->get($config, 'type', $type);
    }

    /**
     * Get name from the configuration file.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getName($config)
    {
        return $this->get($config, 'name');
    }

    /**
     * Get depends from the configuration file.
     *
     * @param $config
     *
     * @return array|mixed|null
     */
    public function getDepends($config)
    {
        $depends = $this->get($config, 'depends', []);

        if (!is_array($depends)) {
            return [
                $depends => "*",
            ];
        }

        return $depends;
    }

    /**
     * Get data from the configuration by the given key.
     *
     * @param      $config
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($config, $key, $default = null)
    {
        return array_get($config, $key, $default);
    }
}
