<?php

namespace ThemeXpert\FormEngine\Transformers;

use ThemeXpert\FormEngine\Contracts\ControlTransformer;

class DividerTransformer extends ControlTransformer
{
    /**
     * Transform the divider.
     *
     * @param $config
     *
     * @return mixed
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
        $c['desc'] = $this->get($config, 'desc', 'Note');
        $c['class'] = $this->getClass($config);

        return $c;
    }

    /**
     * Get icon picker type.
     *
     * @param        $config
     * @param string $type
     *
     * @return string
     */
    public function getType($config, $type = "")
    {
        return "divider";
    }
}
