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
    
    public static function prettyStats(&$aMemcacheStats) {
        if (!is_array($aMemcacheStats)) {
            return false;
        }
        
        $aMemcacheStats['limit_maxbytes'] = display_bytes($aMemcacheStats['limit_maxbytes']);
        $aMemcacheStats['bytes'] = display_bytes($aMemcacheStats['bytes']);
        $aMemcacheStats['bytes_read'] = display_bytes($aMemcacheStats['bytes_read']);
        $aMemcacheStats['bytes_written'] = display_bytes($aMemcacheStats['bytes_written']);
        
        $aMemcacheStats['hit_ratio'] = $aMemcacheStats['get_hits'] / ($aMemcacheStats['get_hits'] + $aMemcacheStats['get_misses']) *100;
        $aMemcacheStats['hit_ratio'] = sprintf('%3.2f', $aMemcacheStats['hit_ratio']) . '%';
    }
    
}