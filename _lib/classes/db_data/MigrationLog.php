<?php

class MigrationLog extends DbData {
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
}