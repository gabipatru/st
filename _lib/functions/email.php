<?php
function email($to, $subject, $body) {
	require_once(CLASSES_DIR . '/phpmailer/class.phpmailer.php');
	
	// configure PHPMailer
	$mail=new PHPMailer();
	$mail->CharSet = 'utf-8';
	$mail->Encoding = "base64";
	$mail->WordWrap = 50;
	
	$mail->IsMail();
	
	$mail->SetFrom(EMAIL_FROM, EMAIL_FROM_NAME);
	$mail->AddReplyTo(EMAIL_FROM, EMAIL_FROM_NAME);
	
	$mail->AddAddress($to);
	
	$mail->Subject = $subject;
	$mail->AltBody = strip_tags($body);
	$mail->MsgHTML($body);
	
	// send the email
	$r = $mail->Send();
	
	// log the data
	$aData = array(
		'subject' => $subject,
		'text' => $body,
		'debug' => ($r ? 'Mesaj Trimis' : 'Mesajul nu a fost trimis')
	);
	$oLogEmail = new LogEmail();
	$oLogEmail->Add($aData);
	
	return true;
}
?>