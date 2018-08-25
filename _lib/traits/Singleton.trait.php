<?php

/*
 * Use this trait if you want your class to be a Singleton
 */
trait Singleton
{
    private static $instance = null;
    
    protected function __construct() { }
    
    private function __clone() { }
    
    private function __wakeup() { }
    
    public static function getSingleton() 
    {
        if (!static::$instance) {
            static::$instance = new static;
        }
        
        if (method_exists(static::$instance, 'onGetSingleton')) {
            static::$instance->onGetSingleton();
        }
        
        return static::$instance;
    }
}