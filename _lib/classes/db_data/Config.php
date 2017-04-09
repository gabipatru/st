<?php

/**
 * Used for different config values
 */

class Config extends dbDataModel {
    const TABLE_NAME    = 'config';
    const ID_FIELD      = 'config_id';
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
    
    public function onAdd($insertId) {
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
            $aSortedData[$aPath[0]][$aPath[1]][$aPath[2]][] = array(
                'config_id' => $aConfigItem['config_id'], 
                'value' => $aConfigItem['value']
            );
        }
        
        return $aSortedData;
    }
}