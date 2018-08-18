<?php

/*
 * This trait provides a function which will send an email
 */
trait Email 
{
    public function sendEmail($to, $subject, $body, $emailQueueId = null) {
        require_once(CLASSES_DIR . '/phpmailer/class.phpmailer.php');
        
        // configure PHPMaile
        $mail=new PHPMailer();
        $mail->CharSet = 'utf-8';
        $mail->Encoding = "base64";
        $mail->WordWrap = 50;
        
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Port = SMTP_PORT;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        
        $fromName	= Config::configByPath('/Email/Email Sending/Email From Name');
        $fromEmail	= Config::configByPath('/Email/Email Sending/Email From');
        
        $mail->SetFrom($fromEmail, $fromName);
        $mail->AddReplyTo($fromEmail);
        
        $mail->AddAddress($to);
        
        $mail->Subject = $subject;
        $mail->AltBody = strip_tags($body);
        $mail->MsgHTML($body);
        
        $mail->SMTPDebug = 2;
        
        // send the email with output buffering for debug info
        ob_start();
        
        $r = $mail->Send();
        
        $debug = ob_get_clean();
        $status = ($r ? EmailLog::STATUS_SENT : EmailLog::STATUS_NOT_SENT);
        
        // log the data
        $oItem = new SetterGetter();
        $oItem->setEmailQueueId($emailQueueId);
        $oItem->setStatus($status);
        $oItem->setErrorInfo($mail->ErrorInfo);
        $oItem->setDebug($debug);
        
        $oLogEmail = new EmailLog();
        $oLogEmail->Add($oItem);
        
        return $r;
    }
}