<?php

class Configuration implements ArrayAccess, Iterator {
    private $jsonObj;
    private $position = 0;

    public function __construct($jsonObj=null) {
        $this->jsonObj = $jsonObj;
        $this->position = 0;
    }

    public function load($configFile) {
        if(!file_exists($configFile) || !is_readable($configFile)) {
            throw new Exception('Configuration file is not readable.');
        }

        $jsonString = file_get_contents($configFile);

        if( !($this->jsonObj = json_decode($jsonString)) ) {
            throw new Exception('Configuration file is not a valid JSON.');
        }
    }

    public function raw() {
        return $this->jsonObj;
    }

    public function offsetSet($offset, $value) {
        //
    }

    public function offsetExists($offset) {
        return ($this->offsetGet($offset) === null);
    }

    public function offsetUnset($offset) {
        //
    }

    public function offsetGet($offset) {
        $path = explode('.', $offset);
        $currentNode = $this->jsonObj;

        foreach($path as $segment) {
            if( is_object($currentNode) && isset($currentNode->$segment) ) {
                $currentNode = $currentNode->$segment;
            } else if( is_array($currentNode) && isset($currentNode[$segment]) ) {
                $currentNode = $currentNode[$segment];
            } else {
                return null;
            }
        }

        return new Configuration($currentNode);
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return new Configuration($this->jsonObj[$this->position]);
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->jsonObj[$this->position]);
    }
}