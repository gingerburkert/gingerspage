<?php

namespace ThemeXpert\FormEngine\Contracts;

interface ControlTransformerInterface
{
    /**
     * @param $config
     *
     * @return mixed
     */
    public function transform($config, $path);
}
