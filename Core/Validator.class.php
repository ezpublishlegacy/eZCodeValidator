<?php

abstract class Validator {
    use Configurable;

    protected $log;

    public function __construct(Log $log) {
        $this->log = $log;
    }

    abstract public function check($filePath);
}