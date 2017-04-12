<?php

/*
 * Used to connect to memcached
 * This class is singleton
 */

class Mcache {
    private static $instance = null;
    private $connetcion = null;
    
    protected function __construct() {
        
    }
    
    private function __clone() {
        
    }
    
    private function __wakeup() {
        
    }
    
    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static;
            static::$instance->connection = new Memcached();
            static::$instance->connection->addServer(MEM_HOST, MEM_PORT);
        }
        
        return static::$instance->connection;
    }
    
}