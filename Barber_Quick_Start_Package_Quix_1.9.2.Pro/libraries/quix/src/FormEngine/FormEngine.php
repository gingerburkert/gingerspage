<?php

namespace ThemeXpert\FormEngine;

class FormEngine
{
    /**
     * Instance of controls transformer.
     *
     * @var ControlsTransformer
     */
    protected $controlsTransformer;

    /**
     * Create a new instance of form engine.
     *
     * @param ControlsTransformer $controlsTransformer
     */
    public function __construct(ControlsTransformer $controlsTransformer)
    {
        $this->controlsTransformer = $controlsTransformer;
    }

    /**
     * Transform the given form.
     *
     * @param $form
     *
     * @return mixed
     */
    public function transform($form, $path)
    {
        foreach ($form as &$controls) {
            $controls = $this->controlsTransformer->transform($controls, $path);
        }

        return $form;
    }
}
