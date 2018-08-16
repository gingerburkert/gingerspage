<?php

namespace ThemeXpert\Assets\Concerns;

trait Filesystem
{
    /**
     * Get contents from the given path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getContents($path)
    {
        return @file_get_contents($path);
    }

    /**
     * Put the given contents in the given file path.
     *
     * @param string $path
     * @param string $contents
     */
    protected function putContents($path, $contents)
    {
        @file_put_contents($path, $contents);
    }

    /**
     * Determine file existence.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function fileExists($path)
    {
        return file_exists($path);
    }
}