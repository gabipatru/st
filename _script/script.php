<?php

/*
 * This class will run an individual cron when called.
 */

namespace Cron;

require_once(__DIR__ . '/_config.php');

/**
 * This script will run all the scripts which have database records.
 */
class Script extends AbstractCron
{
    public function run()
    {
        global $argv;
        $scriptName = ($argv[2] ?? null);
        if (! $scriptName) {
            $this->displayMsg('No script name found !');
            return;
        }

        $cronId = ($argv[3] ?? null);
        if (! $cronId) {
            $this->displayMsg('No script id found !');
            return;
        }

        // class name has a namespace
        $className = 'Cron\\' . $scriptName;

        // check if the cron file exists
        if (! file_exists(SCRIPT_DIR . "/{$scriptName}.php")) {
            $this->displayMsg("The file for cron $scriptName does not exist");
            return;
        }
        require_once SCRIPT_DIR . "/{$scriptName}.php";

        if (! class_exists($className)) {
            $this->displayMsg("There is no class associated with $scriptName");
            return;
        }

        // create Cron Run
        $oConRun = new \CronRun();
        $oItem = new \SetterGetter();
        $oItem->setCronId($cronId);
        $timeStart = time();

        $this->displayMsg("Cron $scriptName started");

        $Cron = new $className($this->db);
        $Cron->run();

        // save the cron run
        $oItem->setDuration(time() - $timeStart);
        $r = $oConRun->Add($oItem);
        if (! $r) {
            $this->displayMsg('Warning - Cron Run was not saved to the database !');
        }

        $this->displayMsg("Cron $scriptName finished");
    }
}

$Script = new Script();
$Script->run();
