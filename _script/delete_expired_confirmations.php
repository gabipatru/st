<?php

/*
 * This class will delete all expired confirmations from the database
 */

namespace Cron;

require_once(__DIR__ . '/_config.php');

class DeleteExpiredConfirmations extends AbstractCron
{
    public function run()
    {
        // fetch the expired confirmations
        $oUserConfirmation = new \UserConfirmation();
        $oConfirmations = $oUserConfirmation->getExpiredUserConfirmations();
        
        $this->displayMsg('Found ' . count($oConfirmations) . ' confirmations');
        
        try {
            $this->db->startTransaction();
            foreach ($oConfirmations as $oConf) {
                $r = $oUserConfirmation->Delete($oConf->getConfirmationId());
                if (!$r) {
                    throw new Exception('Failed to delete confirmation with id ' . $oConf->getConfirmationId());
                }
            }
            $this->db->commitTransaction();
            $this->displayMsg('Deleted all expired confirmations');
        } catch (Exception $e) {
            $this->displayMsg($e->getMessage());
            $this->db->rollbackTransaction();
        }
    }
}

$cron = new DeleteExpiredConfirmations();
//$cron->run();
