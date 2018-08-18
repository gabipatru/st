<?php

/*
 * This trait provides a log to file function
 */
trait Log {
    
    public function logMessage($sMessage, $sFileName = null)
    {
        if (! $sFileName) {
            $sFileName = get_class($this) .'.log';
        }
        
        // open the file
        $rFile = fopen(LOG_PATH.'/'.$sFileName, "a");
        
        return fwrite($rFile, '['.date("Y-m-d H:i:s").'] '.$sMessage."\n");
    }
}