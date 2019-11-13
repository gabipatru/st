<?php

/**
 * Used for storing details about individual cron runs
 */

class CronRun extends DbData
{
    const TABLE_NAME    = 'cron_run';
    const ID_FIELD      = 'cron_run_id';

    protected $aFields = array(
        'cron_run_id',
        'cron_id',
        'duration',
        'created_at'
    );

    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
}
