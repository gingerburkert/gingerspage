<?php

namespace ThemeXpert\FormEngine\Transformers;

use ThemeXpert\FormEngine\Contracts\ControlTransformer;

class TextTransformer extends ControlTransformer
{
    /**
     * Transform text.
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
        $c['placeholder'] = $this->getPlaceholder($config);

        return $c;
    }
}
