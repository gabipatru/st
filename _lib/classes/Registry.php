<?php
/*
 * This class is a singleton which caches all sort of stuff useful in the application
 */

class Registry {
    private static $instance = null;
    private $data = array();
    
    protected function __construct() {
    
    }
    
    private function __clone() {
    
    }
    
    private function __wakeup() {
    
    }
    
    public static function getSingleton() {
        if (!static::$instance) {
            static::$instance = new static;
        }
    
        return static::$instance;
    }
    
    public function set($key, $data, $overwrite = false) {
        if (isset($this->data[$key]) && $overwrite == false) {
            return false;
        }
        $this->data[$key] = $data;
    }
    
    public function get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }
    
    public function clear($key) {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }
}