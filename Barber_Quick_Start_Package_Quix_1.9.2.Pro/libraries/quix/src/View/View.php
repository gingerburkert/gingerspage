<?php

namespace ThemeXpert\View;

use ThemeXpert\View\Engines\PhpEngine;
use ThemeXpert\View\Engines\EngineInterface;

class View
{
    /**
     * Instance of engine interface.
     *
     * @var \ThemeXpert\View\Engines\EngineInterface
     */
    protected $compilerEngine;

    /**
     * Instance of view.
     *
     * @var object
     */
    protected static $instance;

    /**
     * Create a new instance of viw.
     *
     * @param EngineInterface $compilerEngine
     */
    public function __construct(EngineInterface $compilerEngine)
    {
        $this->compilerEngine = $compilerEngine;
    }

    /**
     * Get view instance.
     *
     * @return object|View
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self(new PhpEngine);
        }

        return self::$instance;
    }

    /**
     * Generating view from the given template file.
     *
     * @param $file
     * @param $data
     *
     * @return string
     */
    public function make($file, $data)
    {
        return $this->compilerEngine->get($file, $data);
    }
}
