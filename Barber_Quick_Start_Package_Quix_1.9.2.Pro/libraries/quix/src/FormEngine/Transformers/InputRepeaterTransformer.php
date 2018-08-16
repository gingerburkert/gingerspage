<?php

namespace ThemeXpert\FormEngine\Transformers;

class InputRepeaterTransformer extends TextTransformer
{
    /**
     * Get input repeater type.
     *
     * @param        $config
     * @param string $type
     *
     * @return string
     */
    public function getType($config, $type = "text")
    {
        return "input-repeater";
    }

    /**
     * Get input repeater schema.
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
     * Transform the group repeater.
     *
     * @param $config
     *
     * @return array
     */
    public function transform($config, $path)
    {
        $c = parent::transform($config, $path);

        $c['value'] = $this->getValue($config);
        $c['name'] = $this->getName($config);
        $c['type'] = $this->getType($config);
        $c['label'] = $this->getLabel($config);
        $c['class'] = $this->getClass($config);
        $c['help'] = $this->getHelp($config);
        $c['schema'] = $this->getSchema($config);
        $c['placeholder'] = $this->getPlaceholder($config);

        return $c;
    }
}
