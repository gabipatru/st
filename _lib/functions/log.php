<?php

define ('LOG_PATH', FILES_DIR.'/logs');

function log_message($sFileName, $sMessage) {
	if (!$sFileName) {
		return false;
	}
	
	// open the file
	$rFile = fopen(LOG_PATH.'/'.$sFileName, "a");
	
	return fwrite($rFile, '['.date("Y-m-d H:i:s").'] '.$sMessage."\n");
}

?>