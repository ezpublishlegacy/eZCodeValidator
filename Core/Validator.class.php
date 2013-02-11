<?php

/**
 * Validator template
 */
abstract class Validator extends Configurable {
    protected $log;

    public function __construct(Log $log) {
        $this->log = $log;
    }

    /**
     * Runs file validation.
     * @param  string $filePath file to be validated
     * @return boolean           validation result (success or failure)
     */
    abstract public function check($filePath);
}