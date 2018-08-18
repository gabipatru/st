<?php
/*
 * This class will process the email queue and send emails
 */

require_once(__DIR__ . '/_config.php');

class SendQueuedEmail extends AbstractCron {
    public function run() {
        // fetch the emails to send
        $oEmailQueue = new EmailQueue();
        $oEmails = $oEmailQueue->GetEmailsToProcess();
        
        foreach ($oEmails as $oCurrentEmail) {
            $r = $this->sendEmail(
                $oCurrentEmail->getTo(), 
                $oCurrentEmail->getSubject(), 
                $oCurrentEmail->getBody(), 
                $oCurrentEmail->getEMailQueueId()
            );
            
            $oItem = new SetterGetter();
            if ($r) {
                $oItem->setStatus(EmailQueue::STATUS_SENT);
            }
            else {
                $this->displayMsg("Failed sending queued email with id ". $oCurrentEmail->getEmailQueueId());
            }
            $oItem->setSendAttempts($oCurrentEmail->getSendAttempts() + 1);
            $oItem->setUpdatedAt(date('Y-m-d H:i:s'));
            $oEmailQueue->Edit($oCurrentEmail->getEmailQueueId(), $oItem);
        }
    }
}

$cron = new SendQueuedEmail();
$cron->run();