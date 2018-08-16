<?php

namespace ThemeXpert\Quix\Node\Config;

use ThemeXpert\Config\Contracts\ValidatorInterface;

class Validator implements ValidatorInterface
{
    /**
     * Validate node.
     *
     * @param $config
     * @param $file
     */
    public function validate($config, $file)
    {
        $name = array_get($config, 'name');
        $slug = array_get($config, 'slug');

        if (!$name) {
            xception("node `name` is not defined in `{$file}`");
        }

        if (!$slug) {
            xception("node `slug` is not defined in `{$file}`");
        }
    }
}
