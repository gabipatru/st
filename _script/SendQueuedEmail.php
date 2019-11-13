<?php

/*
 * This class will process the email queue and send emails
 */

namespace Cron;

require_once(__DIR__ . '/_config.php');

class SendQueuedEmail extends AbstractCron
{
    public function run()
    {
        $collectionEmails = $this->getEmailsToProcess();

        $this->displayMsg('Found ' . count($collectionEmails) . ' emails to send');
        
        foreach ($collectionEmails as $oCurrentEmail) {
            $emailQueueId = $oCurrentEmail->getEmailQueueId();

            $r = $this->sendEmail(
                $oCurrentEmail->getToo(),
                $oCurrentEmail->getSubject(),
                $oCurrentEmail->getBody(),
                $emailQueueId
            );
            
            $oItem = new \SetterGetter();
            if ($r) {
                $oItem->setStatus(\EmailQueue::STATUS_SENT);
            } else {
                $this->displayMsg("Failed sending queued email with id " . $oCurrentEmail->getEmailQueueId());
            }
            $oItem->setSendAttempts($oCurrentEmail->getSendAttempts() + 1);
            $oItem->setUpdatedAt(date('Y-m-d H:i:s'));

            $r = $this->updateEmailQueue($emailQueueId, $oItem);
            if (! $r) {
                $this->displayMsg('Failed to update queue status for item with id ' . $emailQueueId);
            }
        }
    }

    /**
     * Get the emails which have to be sent
     *
     * @return \Collection
     */
    protected function getEmailsToProcess(): \Collection
    {
        $oEmailQueue = new \EmailQueue();
        return $oEmailQueue->GetEmailsToProcess();
    }

    /**
     * Update the email queue when an email is sent
     *
     * @param int $emailQueueId
     * @param \SetterGetter $oItem - the item to be updated
     *
     * @return bool - true if the update was successful, false otherwise
     */
    protected function updateEmailQueue(int $emailQueueId, \SetterGetter $oItem): bool
    {
        $oEmailQueue = new \EmailQueue();
        return $oEmailQueue->Edit($emailQueueId, $oItem);
    }
}
