<?php

/**
 * Class that allows object to be configured by merging custom options with default options.
 */
class Configurable {
    /**
     * Default configuration array
     * @var array
     */
    protected $defaultOptions = array();
    /**
     * Object configuration after merging custom options with default ones.
     * @var array
     */
    protected $options = array();

    /**
     * Merges default configuraiton with custom configuration.
     * @param  Configuration $options custom configuration
     */
    public function configure(Configuration $options) {
        $options = (array)$options->raw();

        foreach($this->defaultOptions as $key=>$defaultValue) {
            $this->options[$key] = isset($options[$key]) ? $options[$key] : $defaultValue;
        }
    }
}