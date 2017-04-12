<?php

/**
 * Used for different config values
 */

class Config extends dbDataModel {
    const TABLE_NAME    = 'config';
    const ID_FIELD      = 'config_id';
    
    const MEMCACHE_KEY  = 'CONFIG_ALL_DATA';
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
    
    public function onAdd($insertId) {
        $Memcache = Mcache::getInstance();
        $Memcache->delete(self::MEMCACHE_KEY);
        
        return true;
    }
    public function onEdit($iId, $res) {
        return true;
    }
    public function onSetStatus($iId) {
        return true;
    }
    public function onBeforeDelete($iId) {
        return true;
    }
    public function onDelete($iId) {
        return true;
    }
    
    // rewrite function for memcache support
    public function Get($filters = array(), $options = array()) {
        if (!$filters && !$options) {
            
            // try fetching from memcache
            $Memcache = Mcache::getInstance();
            $config = $Memcache->get(self::MEMCACHE_KEY);
            if ($config) {
                $aConfig = json_decode($config, true);
                return array($aConfig, 0, 0);
            }
            
            // no data in memcache, fetch from DB and save to memcache
            list($aConfig, $nrItems, $maxPage) = parent::Get($filters, $options);
            if ($aConfig) {
                $config = json_encode($aConfig);
                $Memcache->set(self::MEMCACHE_KEY, $config, MEM_EXPIRE_TIME);
            }
            return array($aConfig, $nrItems, $maxPage);
        }
        
        return parent::Get($filters, $options);
    }
    
    // format the data from the database in a convenient format
    public function sortData($data) {
        if (!is_array($data)) {
            return false;
        }
        
        $aSortedData = array();
        foreach ($data as $aConfigItem) {
            $aConfigItem['path'] = trim($aConfigItem['path'], '/');
            $aPath = explode('/', $aConfigItem['path']);
            
            // only valid paths are processed
            if (count($aPath) != 3) {
                continue;
            }
            
            if (!isset($aSortedData[$aPath[0]])) {
                $aSortedData[$aPath[0]] = array();
            }
            if (!isset($aSortedData[$aPath[0]][$aPath[1]])) {
                $aSortedData[$aPath[0]][$aPath[1]] = array();
            }
            if (!isset($aSortedData[$aPath[0]][$aPath[1]][$aPath[2]])) {
                $aSortedData[$aPath[0]][$aPath[1]][$aPath[2]] = array();
            }
            $aSortedData[$aPath[0]][$aPath[1]][$aPath[2]] = array(
                'config_id' => $aConfigItem['config_id'], 
                'value' => $aConfigItem['value']
            );
        }
        
        return $aSortedData;
    }
}