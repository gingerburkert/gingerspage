<?php

namespace ThemeXpert\FormEngine\Transformers;

use ThemeXpert\FormEngine\ControlsTransformer;

class GroupRepeaterTransformer extends TextTransformer
{
    /**
     * Instance of ControlsTransformer.
     *
     * @var \ThemeXpert\FormEngine\ControlsTransforme
     */
    protected $controlsTransformer;

    protected $path;

    /**
     * Create a new instance of group repeater transformer.
     *
     * @param $controlsTransformer
     */
    public function __construct(ControlsTransformer $controlsTransformer)
    {
        $this->controlsTransformer = $controlsTransformer;
    }

    /**
     * Transform the given configuration for the group repeater.
     *
     * @param $config
     *
     * @return array
     */
    public function transform($config, $path)
    {
        $c = parent::transform($config, $path);
        $this->path = $path;

        $c['name'] = $this->getName($config);
        $c['type'] = $this->getType($config);
        $c['label'] = $this->getLabel($config);
        $c['class'] = $this->getClass($config);
        $c['help'] = $this->getHelp($config);
        $c['schema'] = $this->getSchema($config);
        $c['value'] = $this->getValue($config);
        $c['placeholder'] = $this->getPlaceholder($config);

        return $c;
    }

    /**
     * Get the group repeater schema.
     *
     * @param       $config
     * @param array $schema
     *
     * @return array
     */
    public function getSchema($config, $schema = [])
    {
        $schema = $this->get($config, 'schema', $schema);

        $schema = array_map(function ($control) {
            $control['depends'] = $this->getDepends($control);

            return $control;
        }, $schema);

        return $this->controlsTransformer->transform($schema, $this->path);
    }

    /**
     * Get the group repeater value.
     *
     * @param $config
     *
     * @return array
     */
    public function getValue($config)
    {
        $schema = $this->getSchema($config);

        $value = array_map(function ($group) use ($schema) {

            $controls = array_map(function ($control) use ($group) {
                $control['value'] = $this->get($group, $control['name'], $control['value']);

                return $control;
            }, $schema);

            return $this->controlsTransformer->transform($controls, $this->path);
        }, $this->get($config, 'value', []));

        return $value;
    }
}
