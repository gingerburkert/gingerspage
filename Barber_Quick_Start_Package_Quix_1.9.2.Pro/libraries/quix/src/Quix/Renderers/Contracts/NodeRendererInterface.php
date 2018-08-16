<?php

namespace ThemeXpert\Quix\Renderers\Contracts;

interface NodeRendererInterface
{
    public function render($node, $item = null);
}
