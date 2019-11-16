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

    protected function onGet(Collection $oCollection): bool
    {
        // get all cron ids
        $ids = $oCollection->databaseColumn('cron_id');

        // load the required crons
        $oCronModel = new Cron();
        $filters = [ 'cron_id' => $ids ];
        $collectionCron = $oCronModel->Get($filters);

        // bind crons to their runs
        foreach ($oCollection as $oCol) {
            $oCron = $collectionCron->getById($oCol->getCronId());
            $oCol->setCron($oCron);
        }

        return true;
    }
}
