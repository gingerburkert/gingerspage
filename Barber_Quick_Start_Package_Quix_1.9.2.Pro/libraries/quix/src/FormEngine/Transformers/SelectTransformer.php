<?php

namespace ThemeXpert\FormEngine\Transformers;

class SelectTransformer extends TextTransformer
{
    /**
     * Get the type for the select.
     *
     * @param        $modifiedConfigonfig
     * @param string $type
     *
     * @return string
     */
    public function getType($modifiedConfigonfig, $type = "")
    {
        return "select";
    }

    /**
     * Get options for the select.
     *
     * @param $modifiedConfigonfig
     *
     * @return mixed|null
     */
    public function getOptions($modifiedConfigonfig)
    {
        return $this->get($modifiedConfigonfig, 'options', []);
    }

    /**
     * Transform the select.
     *
     * @param $config
     *
     * @return array
     */
    public function transform($config, $path)
    {
        $multiple = $this->get($config, 'multiple', false);

        $options = $this->getOptions($config);

        $options = array_map(function ($value, $label) {
            return compact("value", "label");
        }, array_keys($options), array_values($options));


        $modifiedConfig = parent::transform($config, $path);

        $modifiedConfig['options'] = $options;
        $modifiedConfig['multiple'] = $multiple;

        // set responsive mode,
        // if responsive mode is set as true, then slider responsive mode will be true
        // otherwise it'll be false
        if(!isset($config['responsive'])) $modifiedConfig['responsive'] = false;
        else $modifiedConfig['responsive'] = $config['responsive'];

        $value = [];

        if( $modifiedConfig['responsive']  and !isset($value["responsive_preview"]) ) {
            $value["responsive_preview"] = false;

            $value["responsive"] = $modifiedConfig['responsive'];

            $value["desktop"] = $modifiedConfig['value'];

            $value["tablet"] = $modifiedConfig['value'];

            $value["phone"] = $modifiedConfig['value'];

            //$modifiedConfig['value'] = $value;
        }

        $modifiedConfig['select'] = $value;

        if( isset($config['image']) ) $modifiedConfig['select']['image'] = $config['image'];

        //if($modifiedConfigonfig['name'] == 'title_tag') var_dump($modifiedConfigonfig);

        $modifiedConfig['element_path'] = $path;

        return $modifiedConfig;
    }

    /**
     * Get the value for the select.
     *
     * @param $modifiedConfigonfig
     *
     * @return array|mixed|null
     */
    public function getValue($modifiedConfigonfig)
    {
        if ($this->get($modifiedConfigonfig, 'multiple')) {
            $value = (array)$this->get($modifiedConfigonfig, 'value', []);
        } else {
            $value = $this->get($modifiedConfigonfig, 'value', '');
        }

        return $value;
    }
}
