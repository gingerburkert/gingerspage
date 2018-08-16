<?php

namespace ThemeXpert\FormEngine\Transformers;

class TypographyTransformer extends SliderTransformer
{
    /**
     * Get type for the typography.
     *
     * @param        $config
     * @param string $type
     *
     * @return string
     */
    public function getType($config, $type = "")
    {
        return "typography";
    }

    /**
     * Get typography value.
     *
     * @param $config
     *
     * @return array|mixed|null
     */
    public function getValue($config)
    {
        $value = $this->get($config, "value", null);

        if ($value === null) {
            return [
                "size" => "0",
                "bold" => "",
                "underline" => "",
                "italic" => "",
                "family" => "",
                "case" => "",
                "spacing" => "0",
                "height" => "0",
                "weight" => "",
            ];
        } else {
            $value = (array)$value;
        }

        $value = array_pick($value, [
            "size",
            "bold",
            "underline",
            "italic",
            "family",
            "case",
            "spacing",
            "height",
            "weight",
        ], true); //exclusive

        return $value;
    }

    /**
     * Get desktop value.
     *
     * @param $config
     * @return mixed
     */
    public function getDesktopValue($config)
    {
        return $this->getValue($config)['size'];
    }

    /**
     * Determine element is by default responsive mode.
     *
     * @return bool
     */
    public function isResponsive()
    {
        return true;
    }
}
