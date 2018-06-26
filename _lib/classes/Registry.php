<?php
/*
 * This class is a singleton which caches all sort of stuff useful in the application
 */

class Registry {
    const OVERWRITE         = true;
    
    private static $instance = null;
    private $data = array();
    private $showWarning = true;
    
    protected function __construct() {
    
    }
    
    private function __clone() {
    
    }
    
    private function __wakeup() {
    
    }
    
    public function setShowWarning($value) {
        $this->showWarning = $value;
    }
    
    public function getShowWarning() {
        return $this->showWarning;
    }
    
    public static function getSingleton() {
        if (!static::$instance) {
            static::$instance = new static;
        }
    
        return static::$instance;
    }
    
    public function set($key, $data) {
        if (isset($this->data[$key]) && self::OVERWRITE === false) {
            trigger_error("Not overwriting registry key '$key'!", E_USER_WARNING);
            return false;
        }
        if (isset($this->data[$key]) && $this->getShowWarning() === true) {
            trigger_error("Overwriting registry key '$key'!", E_USER_WARNING);
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