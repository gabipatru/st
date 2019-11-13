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

    public function getCronsToRun()
    {
        $sql = "SELECT *"
                ." FROM ".Cron::TABLE_NAME
                ." WHERE next_runtime < now() + INTERVAL '30 sec'"
                ." AND status = '" . Cron::CRON_ENABLED . "'";

        // run the query
        $res = $this->db->query($sql);
        if (!$res) {
            return new Collection();
        }

        // return a collection
        $oCollection = new Collection();
        while ($row = $this->db->fetchAssoc($res)) {
            $oCollection->add($row[$this->getIdField()], $row);
        }

        return $oCollection;
    }
}
