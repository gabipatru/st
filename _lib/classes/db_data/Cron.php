<?php

/**
 * Used for storing scripts which are being scheduled
 * Allows scheduling many scripts with only 1 crontab line
 */

class Cron extends DbData
{
    const TABLE_NAME    = 'cron';
    const ID_FIELD      = 'cron_id';

    const CRON_ENABLED  = 'enabled';
    const CRON_DISABLED = 'disabled';

    protected $aFields = array(
        'cron_id',
        'script',
        'last_runtime',
        'next_runtime',
        'interval',
        'status',
        'created_at'
    );

    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
}
