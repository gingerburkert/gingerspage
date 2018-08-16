<?php

namespace ThemeXpert\Assets;

class Assets
{
    /**
     * Assets manager.
     *
     * @var object
     */
    protected static $assetsManagerInstance;

    /**
     * Get assets manager instance.
     *
     * @return object
     */
    protected static function getAssetsManagerInstance()
    {
        return new AssetsManager;
    }

    /**
     * Calling all non-static method of the AssetsManager class as staticly.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        if(is_null(self::$assetsManagerInstance)) {
            self::$assetsManagerInstance = new AssetsManager();
        }

      return call_user_func_array([self::$assetsManagerInstance, $method], $arguments);
    }
}
