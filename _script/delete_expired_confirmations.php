<?php
/*
 * This class will delete all expired confirmations from the database
 */

require_once(__DIR__ . '/_config.php');

class DeleteExpiredConfirmations extends AbstractCron {
    public function run() {
        // fetch the expired confirmations
        $oUserConfirmation = new UserConfirmation();
        $oConfirmations = $oUserConfirmation->getExpiredUserConfirmations();
        
        $this->displayMsg('Found '. count($oConfirmations) .' confirmations');
        
        try {
            db::startTransaction();
            foreach ($oConfirmations as $oConf) {
                $r = $oUserConfirmation->Delete($oConf->getConfirmationId());
                if (!$r) {
                    throw new Exception('Failed to delete confirmation with id '. $oConf->getConfirmationId());
                }
            }
            db::commitTransaction();
            $this->displayMsg('Deleted all expired confirmations');
        }
        catch (Exception $e) {
            $this->displayMsg($e->getMessage());
            db::rollbackTransaction();
        }
        
    }
}

$cron = new DeleteExpiredConfirmations();
$cron->run();