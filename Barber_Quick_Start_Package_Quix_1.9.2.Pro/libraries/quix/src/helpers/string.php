<?php


if (!function_exists('startsWith')) {
    /**
     * Starts with.
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    function startsWith($haystack, $needle)
    {
        # search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}

if (!function_exists('endsWith')) {
    /**
     * Ends with.
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    function endsWith($haystack, $needle)
    {
        # search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle,
                $temp) !== false);
    }
}

if (!function_exists('trailingslashit')) {
    /**
     * @param $string
     *
     * @return string
     */
    function trailingslashit($string)
    {
        return untrailingslashit($string) . '/';
    }
}

if (!function_exists('untrailingslashit')) {
    /**
     * @param $string
     *
     * @return string
     */
    function untrailingslashit($string)
    {
        return rtrim($string, '/\\');
    }
}

/**
 * Get class names.
 *
 * @return string
 */
function classNames()
{
    $args = func_get_args();

    $classes = array_map(function ($arg) {
        if (is_array($arg)) {
            return implode(" ", array_filter(array_map(function ($expression, $class) {
                return $expression ? $class : false;
            }, $arg, array_keys($arg))));
        }

        return $arg;
    }, $args);

    return implode(" ", array_filter($classes));
}

/**
 * Get the class visibility from the given visibility.
 * @param $visibility
 *
 * @return string
 */
function visibilityClasses($visibility)
{
    return classNames([
        'qx-hidden-lg' => !$visibility['lg'],
        'qx-hidden-md' => !$visibility['md'],
        'qx-hidden-sm' => !$visibility['sm'],
        'qx-hidden-xs' => !$visibility['xs'],
    ]);
}
