<?php
/*
 * Breadcrumbs for all pages
 * This class is a singleton
 */
class Breadcrumbs {
    
    private static $instance = null;
    
    private $breadcrumbs = array();
    
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
    
    public function Add($name, $link) {
        $this->breadcrumbs[] = array('name' => $name, 'link' => $link);
    }
    
    public function getBreadcrumbs() {
        return $this->breadcrumbs;
    }
}