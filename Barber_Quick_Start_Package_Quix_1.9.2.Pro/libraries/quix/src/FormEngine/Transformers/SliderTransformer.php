<?php

namespace ThemeXpert\FormEngine\Transformers;

class SliderTransformer extends TextTransformer
{
    /**
     * @var int
     */
    protected $defaultTabletValue= 0;

    /**
     * @var int
     */
    protected $defaultPhoneValue= 0;

    /**
     * Transform the slider.
     *
     * @param $config
     *
     * @return array
     */
    public function transform($config, $path)
    {
        $c = parent::transform($config, $path);

        $c['name'] = $this->getName($config);
        $c['type'] = $this->getType($config);
        $c['label'] = $this->getLabel($config);
        $c['class'] = $this->getClass($config);
        $c['help'] = $this->getHelp($config);
        $c['suffix'] = $this->getSuffix($config);
        $c['min'] = $this->getMin($config);
        $c['max'] = $this->getMax($config);
        $c['step'] = $this->getStep($config);
        $c['value'] = $this->getValue($config);

        if ($this->getDepends($config)) $c['depends'] = $this->getDepends($config);

        // set responsive mode,
        // if responsive mode is set as true, then slider responsive mode will be true
        // otherwise it'll be false
        if(!isset($config['responsive'])) $c['responsive'] = false;
        else $c['responsive'] = $config['responsive'];

        if(method_exists($this, 'isResponsive')) {
            $c['responsive'] = $this->isResponsive();
        }

        if(is_array($this->getValue($config))) {
            $c['value'] = $this->getValue($config);
        }

        $c['slider'] = [
            'responsive' => $c['responsive']
        ];

        if($this->isResponsived($c)) {
            $c['slider']['desktop'] = is_array($this->getValue($config))
                                      ? $this->getDesktopValue($config)
                                      : (string) $this->getValue($config);

            if(!$this->isResponsivePreviewModed($c)) {
                $c['slider']["responsive_preview"] = false;

                $c['slider']["tablet"] = $this->defaultTabletValue;

                $c['slider']["phone"] = $this->defaultPhoneValue;
            }

            //$c['value'] = $c['slider'];
        }

        return $c;
    }

    /**
     * Get max value.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getMax($config)
    {
        return $this->get($config, 'max', 100);
    }

    /**
     * Get suffix.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getSuffix($config)
    {
        return $this->get($config, 'suffix', "");
    }

    /**
     * Get min value.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getMin($config)
    {
        return $this->get($config, 'min', 0);
    }

    /**
     * Get step.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getStep($config)
    {
        return $this->get($config, 'step', 1);
    }

    /**
     * Get slider value.
     *
     * @param $config
     *
     * @return mixed|null
     */
    public function getValue($config)
    {
        return $this->get($config, 'value', 0);
    }

    /**
     * Determine responsive mode.
     *
     * @param $c
     * @return mixed
     */
    protected function isResponsived($c)
    {
        return $c['slider']['responsive'] == true;
    }

    /**
     * Determine responsive preview mode.
     *
     * @param $c
     * @return bool
     */
    protected function isResponsivePreviewModed($c)
    {
        return isset($c['slider']["responsive_preview"]);
    }

    /**
     * Get desktop value.
     *
     * @return int
     */
    public function getDesktopValue($config)
    {
        return 0;
    }
}
