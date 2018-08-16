<?php

namespace ThemeXpert\Config;

use ThemeXpert\Config\Contracts\ValidatorInterface;

class Validator implements ValidatorInterface
{
    /**
     * Validate configuration file.
     *
     * @param $config
     * @param $file
     */
    public function validate($config, $file)
    {
        $name = array_get($config, 'name');
        $slug = array_get($config, 'slug');

        if (!$name) {
            xception("element `name` is not defined in `{$file}`");
        }

        if (!$slug) {
            xception("element `slug` is not defined in `{$file}`");
        }
    }
}
