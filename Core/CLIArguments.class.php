<?php

class CLIArguments implements ArrayAccess {
    private $options = array();

    public function __construct($arguments) {
        $currentParam = null;

        foreach($arguments as $argument) {
            if(startsWith($argument, '--')) {
                $currentParam = ltrim($argument, '-');
                $this->options[$currentParam] = array();
            } else if($currentParam !== null) {
                $this->options[$currentParam][] = $argument;
            }
        }
    }

    public function offsetSet($offset, $value) {
        //read only
    }

    public function offsetExists($offset) {
        return isset($this->options[$offset]);
    }

    public function offsetUnset($offset) {
        //read only
    }

    public function offsetGet($offset) {
        if($this->offsetExists($offset)) {
            return  $this->options[$offset];
        }

        return null;
    }
}