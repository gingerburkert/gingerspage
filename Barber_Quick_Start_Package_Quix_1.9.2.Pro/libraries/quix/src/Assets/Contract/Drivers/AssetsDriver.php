<?php

namespace ThemeXpert\Assets\Contract\Drivers;

interface AssetsDriver
{
    /**
     * Added javascript with the application.
     *
     * @param string $handle
     * @param string $src
     * @param array  $data
     * @param array  $dependencies
     * @param null   $order
     * @param null   $version
     *
     * @throws AssetException
     */
    public function Js($handle, $src, $data = [], $dependencies = [], $order = null, $version = null);

    /**
     * Added stylesheet with the application.
     *
     * @param string $handle
     * @param string $src
     * @param array  $data
     * @param array  $dependencies
     * @param null   $order
     * @param null   $version
     * @param string $media
     *
     * @throws AssetException
     */
    public function Css($handle, $src, $data = [], $dependencies = [], $order = null, $version = null, $media = 'all');
}
