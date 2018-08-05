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
    
    public static function getSingleton() {
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
        
        $View = View::getSingleton();
        
        $aMemcacheStats['limit_maxbytes'] = $View->displayBytes($aMemcacheStats['limit_maxbytes']);
        $aMemcacheStats['bytes'] = $View->displayBytes($aMemcacheStats['bytes']);
        $aMemcacheStats['bytes_read'] = $View->displayBytes($aMemcacheStats['bytes_read']);
        $aMemcacheStats['bytes_written'] = $View->displayBytes($aMemcacheStats['bytes_written']);
        
        $aMemcacheStats['hit_ratio'] = $aMemcacheStats['get_hits'] / ($aMemcacheStats['get_hits'] + $aMemcacheStats['get_misses']) *100;
        $aMemcacheStats['hit_ratio'] = sprintf('%3.2f', $aMemcacheStats['hit_ratio']) . '%';
    }
    
}