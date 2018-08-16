<?php

namespace ThemeXpert\FileSystem;

use DirectoryIterator;

class FileSystem
{
    /**
     * Get all directories of the given path.
     *
     * @param $path
     *
     * @return array
     */
    public static function folders($path)
    {
        $folders = [];

        foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $folders[] = $file->getFilename();
            }
        }

        return $folders;
    }

    /**
     * if extension is given returns an array of files with that
     * extension
     *
     * @param      $path
     * @param null $ext
     *
     * @return array
     */
    public static function files($path, $ext = null)
    {
        $files = scandir($path);


        $files = array_filter($files, function ($file) {
            return substr($file, 0, 1) !== ".";
        });

        if (!$ext) {
            return $files;
        }

        return array_filter($files, function ($file) use ($ext) {
            $fileInfo = pathinfo($file);

            return $fileInfo['extension'] === $ext;
        });
    }

    /**
     * Determine the file existence.
     * @param $file
     *
     * @return bool
     */
    public static function exists($file)
    {
        return file_exists($file);
    }

}
