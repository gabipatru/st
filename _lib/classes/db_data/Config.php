<?php

/**
 * Used for different config values
 */

class Config extends DbData {
    const TABLE_NAME    = 'config';
    const ID_FIELD      = 'config_id';
    
    const CONFIG_VALUE_YES  = '1';
    const CONFIG_VALUE_NO   = '0';
    
    const MEMCACHE_KEY      = 'CONFIG_ALL_DATA';
    const REGISTRY_KEY      = 'CONFIG';
    const REGISTRY_KEY_PATH = 'CONFIG_PATH';
    
    protected $aFields = array(
        'config_id',
        'path',
        'value',
        'type'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
    
    public function onAdd($insertId) {
        $Memcache = Mcache::getSingleton();
        $Memcache->delete(self::MEMCACHE_KEY);
        
        return true;
    }
    
    // rewrite function for memcache support
    public function Get($filters = array(), $options = array()) {
        if (!$filters && !$options) {
            // try fetching from Registry
            $oRegistry = Registry::getSingleton();
            $oConfigCollection = $oRegistry->get(self::REGISTRY_KEY);
            if ($oConfigCollection !== null && count($oConfigCollection)) {
                return $oConfigCollection;
            }
            
            // try fetching from memcache
            $Memcache = Mcache::getSingleton();
            $config = $Memcache->get(self::MEMCACHE_KEY);
            if ($config) {
                $aConfig = json_decode($config, true);
                $oConfig = new Collection();
                $oConfig->fromArray($aConfig);
                return $oConfig;
            }
            
            // no data in memcache, fetch from DB and save to memcache
            $oConfig = parent::Get($filters, $options);
            if (count($oConfig)) {
                $config = json_encode($oConfig->toArray());
                $Memcache->set(self::MEMCACHE_KEY, $config, MEM_EXPIRE_TIME);
            }
            return $oConfig;
        }
        
        return parent::Get($filters, $options);
    }
    
    // format the data from the database in a convenient format
    public function sortData($oConfigCollection) {
        if (!is_object($oConfigCollection)) {
            return false;
        }
        
        $aSortedData = array();
        foreach ($oConfigCollection as $oItem) {
            $path = trim($oItem->getPath(), '/');
            $aPath = explode('/', $path);
            
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
                'config_id' => $oItem->getConfigId(), 
                'type' => $oItem->getType(),
                'value' => $oItem->getValue()
            );
        }
        
        return $aSortedData;
    }
    
    // fetch an array indexed by config path
    public function indexByPath() {
        $oCollection = $this->Get();
        $aIndex = array();
        
        foreach ($oCollection as $oItem) {
            $aIndex[$oItem->getPath()] = $oItem->getValue();
        }
        
        return $aIndex;
    }
    
    // get config by path
    public static function configByPath($path) {
        $oRegsitry = Registry::getSingleton();
        $aIndex = $oRegsitry->get(Config::REGISTRY_KEY_PATH);

        if (isset($aIndex[$path])) {
            return $aIndex[$path];
        }
        return null;
    }
    
    public function reInit()
    {
        // clear memcache
        $oMemcache = Mcache::getSingleton();
        $oMemcache->delete(self::MEMCACHE_KEY);
        
        $oRegsitry = Registry::getSingleton();
        $oRegsitry->clear(self::REGISTRY_KEY);
        $oRegsitry->clear(self::REGISTRY_KEY_PATH);
        
        $oConfigCollection = $this->Get();
        $aConfigIndex = $this->indexByPath();
        
        $oRegsitry->set(self::REGISTRY_KEY, $oConfigCollection);
        $oRegsitry->set(self::REGISTRY_KEY_PATH, $aConfigIndex);
        $oRegsitry->setShowWarning(true);
    }
}