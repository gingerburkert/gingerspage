<?php 

namespace ThemeXpert;

class Application
{
    /**
     * Assets driver name.
     * 
     * @var string
     */
    protected $assetsDriver;

    /**
     * Run Quix application
     */
    public function run()
    {
        # Bootstrapping application
        $this->bootstrap();
    }

    /**
     * Bootstrapping application required file.
     */
    protected function bootstrap()
    {
        # Bootstrapping application required assets
        $this->bootstrapAssets();
    }

    /**
     * Bootstrapping assets.
     */
    protected function bootstrapAssets()
    {
        $methodName = "load{$this->assetsDriver}Assets";

        $this->{$methodName}();
    }

    /**
     * To load all joomla assets that required for the Quix application
     */
    protected function loadJoomlaAssets()
    {
        jimport('joomla.application.component.helper');
        jimport('quix.app.drivers.joomla.functions');
        jimport('quix.app.drivers.joomla.joomla');
        jimport('quix.app.drivers.joomla.template');
        jimport('quix.app.drivers.joomla.css');
    }

    /**
     * To load all wordpress assets that required for the Quix application
     */
    protected function loadWordpressAssets()
    {
        require __DIR__ . '/../app/drivers/wordpress/functions.php';
        require __DIR__ . '/../app/drivers/wordpress/wordpress.php';
        require __DIR__ . '/../app/drivers/wordpress/template.php';
        require __DIR__ . '/../app/drivers/wordpress/css.php';
    }

    /**
     * To load all grav assets that required for the Quix application
     */
    protected function loadGravAssets()
    {
        // grav assets loader goes to here...
    }

    /**
     * Set asset platform name.
     *
     * @param $platform
     */
    public function setAssetPlatform($platform)
    {
        $this->assetsDriver = $platform;
    }
}