<?php

namespace ThemeXpert\Config\Contracts;

interface TransformerInterface
{
    public function transform($config, $path = null);
}
