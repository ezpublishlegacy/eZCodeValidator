<?php

trait Configurable {
    protected $defaultOptions = array();
    protected $options = array();

    public function configure(Configuration $options) {
        $options = (array)$options->raw();

        foreach($this->defaultOptions as $key=>$defaultValue) {
            $this->options[$key] = isset($options[$key]) ? $options[$key] : $defaultValue;
        }
    }
}