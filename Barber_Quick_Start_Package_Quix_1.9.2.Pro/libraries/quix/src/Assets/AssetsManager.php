<?php

namespace ThemeXpert\Assets;

class AssetsManager
{
    /**
     * Driver name.
     *
     * @var string
     */
    protected $driver;

    /**
     * Driver instance.
     *
     * @var object
     */
    protected $driverInstance;

    /**
     * Create a new instance of assets driver.
     */
    public function __construct()
    {
        $this->driver = ASSETS_DRIVER;
    }

    /**
     * Get assets driver name.
     *
     * @return string
     */
    public function getDriverName()
    {
        return $this->driver;
    }

    /**
     * Get driver instance.
     *
     * @return object
     */
    protected function getDriverInstance()
    {
        if(is_null($this->driverInstance)) {
            $driver = "ThemeXpert\\Assets\\Drivers\\{$this->driver}";

            $this->driverInstance = new $driver;
        }

        return $this->driverInstance;
    }

    /**
     * Dynamically calling drivers method.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $instance = $this->getDriverInstance();

        return call_user_func_array([$instance, $method], $arguments);
    }
}
