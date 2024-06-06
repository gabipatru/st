<?php

/**
 * Use this class when you create a new script/cron
 * Don't forget to modify the log config in common/config
 */

namespace Cron;

require_once(TRAITS_DIR . '/Html.trait.php');
require_once(TRAITS_DIR . '/Log.trait.php');
require_once(TRAITS_DIR . '/Email.trait.php');
require_once(__DIR__ . '/cron.trait.php');

abstract class AbstractCron extends \SetterGetter
{
    use \Log;
    use \Email;
    use CronDisplayMsg;
    
    protected $db;
    
    // Warning emails will be sent to this address by the sendWarningEmail function
    // Overwrite in child classes to send to a different address if needed
    protected $warningEmailAddress = CRON_WARNING_EMAIL_ADDRESS;
    
    abstract public function run();
    
    /*
     * Constructor for scripts / crons
     */
    public function __construct()
    {
        // autoload
        require_once(CLASSES_DIR . '/mvc.php');
        spl_autoload_register('mvc::autoload');
        
        $this->checkDebugOption();
        
        $this->displayMsg("\n***********************************");
        $this->displayMsg('Cron started at ' . date('Y-m-d H:i:s') . "\n");
        
        $this->db = \db::getSingleton();
        if (! $this->db->isConnected()) {
            $this->db->connect();
        }
        
        // config setup
        $oConfig = new \Config();
        $oConfigCollection = $oConfig->Get();
        $aConfigIndex = $oConfig->indexByPath();
        
        $oRegsitry = \Registry::getSingleton();
        $oRegsitry->clear(\Config::REGISTRY_KEY);
        $oRegsitry->clear(\Config::REGISTRY_KEY_PATH);
        $oRegsitry->set(\Config::REGISTRY_KEY, $oConfigCollection);
        $oRegsitry->set(\Config::REGISTRY_KEY_PATH, $aConfigIndex);
    }
    
    /*
     * Destructor for scripts / crons
     */
    public function __destruct()
    {
        $this->displayMsg("\nCron ended at " . date('Y-m-d H:i:s'));
        $this->displayMsg("***********************************\n");
    }
    
    /*
     * This function will stop php execution if environment from which it is run is not command line interface
     */
    protected function onlyRunFromCommandLine()
    {
        if (php_sapi_name() != 'cli') {
            die("Please run this from the commandline.\n");
        }
    }
    
    /*
     * Get the warning email address
     */
    public function getWarningEmailAddress()
    {
        return $this->warningEmailAddress;
    }
    
    /*
     * Send a warning email to it@dred.com.
     * Use in case error occur in the script and company staff must be notified
     */
    public function sendWarningEmail($subject, $message)
    {
        $this->sendEmail($this->getWarningEmailAddress(), $subject, $message);
    }
}
