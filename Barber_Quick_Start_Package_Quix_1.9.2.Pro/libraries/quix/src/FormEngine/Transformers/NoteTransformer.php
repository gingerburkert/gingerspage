<?php

namespace ThemeXpert\FormEngine\Transformers;

use ThemeXpert\FormEngine\Contracts\ControlTransformer;

class NoteTransformer extends ControlTransformer
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
        return "note";
    }
}
