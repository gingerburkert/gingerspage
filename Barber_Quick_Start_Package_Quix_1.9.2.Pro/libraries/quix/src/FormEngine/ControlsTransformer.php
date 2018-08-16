<?php

namespace ThemeXpert\FormEngine;

use ThemeXpert\FormEngine\Transformers\TextTransformer;
use ThemeXpert\FormEngine\Transformers\NoteTransformer;
use ThemeXpert\FormEngine\Transformers\LinkTransformer;
use ThemeXpert\FormEngine\Transformers\ImageTransformer;
use ThemeXpert\FormEngine\Transformers\SliderTransformer;
use ThemeXpert\FormEngine\Transformers\SwitchTransformer;
use ThemeXpert\FormEngine\Transformers\MarginTransformer;
use ThemeXpert\FormEngine\Transformers\EditorTransformer;
use ThemeXpert\FormEngine\Transformers\SelectTransformer;
use ThemeXpert\FormEngine\Transformers\DividerTransformer;
use ThemeXpert\FormEngine\Transformers\TextareaTransformer;
use ThemeXpert\FormEngine\Transformers\IconPickerTransformer;
use ThemeXpert\FormEngine\Transformers\DatePickerTransformer;
use ThemeXpert\FormEngine\Transformers\CodeTransformer;
use ThemeXpert\FormEngine\Transformers\TimePickerTransformer;
use ThemeXpert\FormEngine\Transformers\FileManagerTransformer;
use ThemeXpert\FormEngine\Transformers\TypographyTransformer;
use ThemeXpert\FormEngine\Transformers\ColorPickerTransformer;
use ThemeXpert\FormEngine\Transformers\GroupRepeaterTransformer;
use ThemeXpert\FormEngine\Transformers\InputRepeaterTransformer;

class ControlsTransformer
{
    /**
     * Create a new instance of controls transform.
     */
    public function __construct()
    {
        $this->dividerTransformer = new DividerTransformer();

        $this->noteTransformer = new NoteTransformer();

        $this->editorTransformer = new EditorTransformer();

        $this->textTransformer = new TextTransformer();

        $this->selectTransformer = new SelectTransformer();

        $this->switchTransformer = new SwitchTransformer();

        $this->textareaTransformer = new TextareaTransformer();

        $this->colorPickerTransformer = new ColorPickerTransformer();

        $this->groupRepeaterTransformer = new GroupRepeaterTransformer($this);

        $this->inputRepeaterTransformer = new InputRepeaterTransformer();

        $this->sliderTransformer = new SliderTransformer();

        $this->marginTransformer = new MarginTransformer();

        $this->typographyTransformer = new TypographyTransformer();

        $this->datePickerTransformer = new DatePickerTransformer();

        $this->timePickerTransformer = new TimePickerTransformer();

        $this->iconPickerTransformer = new IconPickerTransformer();

        $this->fileManagerTransformer = new FileManagerTransformer();

        $this->codeTransformer = new CodeTransformer();

        $this->linkTransformer = new LinkTransformer();

        $this->imageTransformer = new ImageTransformer();
    }

    /**
     * Transform the given controls.
     *
     * @param $controls
     *
     * @return array
     */
    public function transform($controls, $path)
    {
       // return array_map([$this, 'transformControl'], [$controls, $path]);

        return array_map(function($control) use ($path) {
            return $this->transformControl($control, $path);
        }, $controls);
    }

    /**
     * Transform control.
     *
     * @param $control
     *
     * @return array
     */
    public function transformControl($control, $path)
    {
        switch (array_get($control, 'type')) {
            case "editor":
                return $this->editorTransformer->transform($control, $path);
            case "select":
                return $this->selectTransformer->transform($control, $path);
            case "image":
                return $this->imageTransformer->transform($control, $path);
            case "textarea":
                return $this->textareaTransformer->transform($control, $path);
            case "link":
                return $this->linkTransformer->transform($control, $path);
            case "note":
                return $this->noteTransformer->transform($control, $path);
            case "divider":
                return $this->dividerTransformer->transform($control, $path);
            case "switch":
                return $this->switchTransformer->transform($control, $path);
            case "group-repeater":
                return $this->groupRepeaterTransformer->transform($control, $path);
            case "input-repeater":
                return $this->inputRepeaterTransformer->transform($control, $path);
            case "color":
            case "colorpicker":
                return $this->colorPickerTransformer->transform($control, $path);
            case "date":
                return $this->datePickerTransformer->transform($control, $path);
            case "time":
                return $this->timePickerTransformer->transform($control, $path);
            case "file-manager":
                return $this->fileManagerTransformer->transform($control, $path);
            case "code":
                return $this->codeTransformer->transform($control, $path);
            case "icon":
            case "iconpicker":
                return $this->iconPickerTransformer->transform($control, $path);
            case "slider":
                return $this->sliderTransformer->transform($control, $path);
            case "typography":
                return $this->typographyTransformer->transform($control, $path);
            case "margin":
            case "padding":
                return $this->marginTransformer->transform($control, $path);
            default:
                return $this->textTransformer->transform($control, $path);
        }
    }
}
