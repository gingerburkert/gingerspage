<?php

use ThemeXpert\View\View;

/**
 * Rendering view from the given view file.
 *
 * @param $file
 * @param $data
 *
 * @return string
 */
function wpvention_view($file, $data)
{
    return View::getInstance()->make($file, $data);
}
