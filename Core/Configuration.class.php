<?php

/**
 * Reads JSON configuration file and allows to access it using array operator [] and iterate over it.
 */
class Configuration implements ArrayAccess, Iterator {
    private $jsonObj;
    private $position = 0;

    public function __construct($jsonObj=null) {
        $this->jsonObj = $jsonObj;
        $this->position = 0;
    }

    /**
     * Loads JSON data from file.
     * @param  string $configFile path to config file
     */
    public function load($configFile) {
        if(!file_exists($configFile) || !is_readable($configFile)) {
            throw new Exception('Configuration file is not readable.');
        }

        $jsonString = file_get_contents($configFile);

        if( !($this->jsonObj = json_decode($jsonString)) ) {
            throw new Exception('Configuration file is not a valid JSON.');
        }
    }

    /**
     * Returns raw data (Object/Array/String/Number/Boolean) instead of data packed in Configuration class.
     */
    public function raw() {
        return $this->jsonObj;
    }


    /**
     * ArrayAccess methods
     */

    public function offsetSet($offset, $value) {
        //read only
    }

    public function offsetExists($offset) {
        return ($this->offsetGet($offset) === null);
    }

    public function offsetUnset($offset) {
        //read only
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

    /**
     * Iterator methods
     */

    public function rewind() {
        $this->position = 0;
    }

    /**
     * Returns part of configuration wrapped in Configuration object.
     * @return Configuration requested data wrapped in Configuration
     */
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