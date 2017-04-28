<?php
function email($to, $subject, $body) {
	require_once(CLASSES_DIR . '/phpmailer/class.phpmailer.php');
	
	// configure PHPMailer
	$mail=new PHPMailer();
	$mail->CharSet = 'utf-8';
	$mail->Encoding = "base64";
	$mail->WordWrap = 50;
	
	$mail->IsMail();
	
	$mail->SetFrom(Config::configByPath('/Email/Email Sending/Email From'));
	$mail->AddReplyTo(Config::configByPath('/Email/Email Sending/Email From Name'));
	
	$mail->AddAddress($to);
	
	$mail->Subject = $subject;
	$mail->AltBody = strip_tags($body);
	$mail->MsgHTML($body);
	
	$mail->SMTPDebug = 1;
	
	// send the email with output buffering for debug info
	ob_start();
	
	$r = $mail->Send();
	
	$debug = ob_get_clean();
	$status = ($r ? EmailLog::STATUS_SENT : EmailLog::STATUS_NOT_SENT);
	
	// log the data
	$oItem = new SetterGetter();
	$oItem->setTo($to);
	$oItem->setSubject($subject);
	$oItem->setBody($body);
	$oItem->setStatus($status);
	$oItem->setErrorInfo($mail->ErrorInfo);
	$oItem->setDebug($debug);
	
	$oLogEmail = new EmailLog();
	$oLogEmail->Add($oItem);
	
	return $r;
}
?>