<?php
define('MIGRATIONS_LOG_TABLE_NAME', 'migrations_log');
define('MIGRATIONS_LOG_ID_FIELD', 'migration_log_id');

class MigrationsLog extends dbDataModel {
    function __construct($table = MIGRATIONS_LOG_TABLE_NAME, $id = MIGRATIONS_LOG_ID_FIELD, $status = '') {
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