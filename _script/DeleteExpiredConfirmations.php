<?php

/*
 * This class will delete all expired confirmations from the database
 */

namespace Cron;

require_once(__DIR__ . '/_config.php');

class DeleteExpiredConfirmations extends AbstractScript
{
    public function run()
    {
        // fetch the expired confirmations
        $collectionConfirmarions = $this->getExpiredConfirmations();
        
        $this->displayMsg('Found ' . count($collectionConfirmarions) . ' confirmations');
        
        try {
            $this->db->startTransaction();
            foreach ($collectionConfirmarions as $oConf) {
                $r = $this->deleteConfirmation($oConf);
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

    /**
     * This function fetches the expired confirmations
     *
     * @return \Collection
     */
    protected function getExpiredConfirmations(): \Collection
    {
        $oUserConfirmation = new \UserConfirmation();
        return $oUserConfirmation->getExpiredUserConfirmations();
    }

    /**
     * Delete a user confirmation
     *
     * @param \SetterGetter $confirmation - the confirmation to be deleted
     *
     * @return bool - if the delete was successful or not
     */
    protected function deleteConfirmation(\SetterGetter $confirmation): bool
    {
        $oUserConfirmation = new \UserConfirmation();
        return $oUserConfirmation->Delete($confirmation->getConfirmationId());
    }
}
