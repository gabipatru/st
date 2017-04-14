<?php

class MigrationLog extends dbDataModel {
    const TABLE_NAME    = 'migration_log';
    const ID_FIELD      = 'migration_log_id';
    
    protected $aFields = array(
        'migration_log_id',
        'migration_id',
        'query',
        'duration',
        'created_at'
    );
    
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