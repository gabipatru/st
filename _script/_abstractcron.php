<?php

/**
 * Use this class when you create a new script/cron
 * Don't forget to modify the log config in common/config
 */

abstract class AbstractCron extends SetterGetter {
    
    protected $db;
    
    // Warning emails will be sent to this address by the sendWarningEmail function
    // Overwrite in child classes to send to a different address if needed
    protected $warningEmailAddress = 'crons@mvc.ro';
    
    abstract function run();
    
    /*
     * Constructor for scripts / crons
     */
    public function __construct($loggerName = null) {
        // this is for logging
        if (!$loggerName) {
            $loggerName = get_class($this);
        }
        $this->setLogName($loggerName);
        
        $this->checkDebugOption();
        
        $this->displayMsg('*******************************');
        $this->displayMsg('Cron started at ' . date('Y-m-d H:i:s'));
        
        db::connect();
    }
    
    /*
     * Destructor for scripts / crons
     */
    public function __destruct() {
        $this->displayMsg('Cron ended at ' . date('Y-m-d H:i:s'));
        $this->displayMsg('********************************');
    }
    
    /*
     * This function will stop php execution if environment from which it is run is not command line interface
     */
    protected function onlyRunFromCommandLine() {
        if (php_sapi_name() != 'cli') {
            die("Please run this from the commandline.\n");
        }
    }
    
    /*
     * Check if the script received the debug option, and set the debug to appropriate value
     */
    protected function checkDebugOption() {
        global $argv;
        
        if (!empty($argv[1]) && $argv[1] === 'debug') {
            $this->setDebug(true);
        }
        else {
            $this->setDebug(false);
        }
    }
    
    /*
     * Use this function to display a message or log it to file
     */
    public function displayMsg($message) {
        if ($this->getDebug()) {
            echo $message . "\n";
        } else {
            log_message($this->getLogName(), $sMessage);
        }
    }
    
    /*
     * Get the warning email address
     */
    public function getWarningEmailAddress() {
        return $this->warningEmailAddress;
    }
    
    /*
     * Send a warning email to it@dred.com.
     * Use in case error occur in the script and company staff must be notified
     */
    public function sendWarningEmail($subject, $message) {
        $mail = new MandrillMailer();
        $mail->AddAddress($this->getWarningEmailAddress());
        $mail->Subject = $subject;
        $mail->Body = $message;
        if(!$mail->send()) {
            $this->displayMsg("Mailer Error: " . $mail->ErrorInfo);
        }
    }
}