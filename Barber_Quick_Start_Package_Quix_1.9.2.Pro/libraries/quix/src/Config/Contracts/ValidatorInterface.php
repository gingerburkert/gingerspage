<?php

namespace ThemeXpert\Config\Contracts;

interface ValidatorInterface
{
    public function validate($config, $file);
}
