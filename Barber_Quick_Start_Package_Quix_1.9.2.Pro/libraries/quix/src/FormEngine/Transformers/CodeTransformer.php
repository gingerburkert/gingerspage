<?php

namespace ThemeXpert\FormEngine\Transformers;

class CodeTransformer extends TextTransformer
{
    /**
     * Get code type.
     *
     * @param        $config
     * @param string $type
     *
     * @return string
     */
    public function getType($config, $type = "")
    {
        return "code";
    }
}
