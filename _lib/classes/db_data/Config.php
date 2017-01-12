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
}