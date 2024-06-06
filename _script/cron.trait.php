<?php

namespace Cron;

trait CronDisplayMsg
{
    /*
     * Check if the script received the debug option, and set the debug to appropriate value
     */
    protected function checkDebugOption()
    {
        global $argv;

        if (!empty($argv[1]) && $argv[1] === 'debug') {
            $this->setDebug(true);
        } else {
            $this->setDebug(false);
        }
    }

    /*
     * Use this function to display a message or log it to file
     */
    public function displayMsg($message)
    {
        if ($this->getDebug()) {
            echo $message . "\n";
        } else {
            $this->logMessage($message);
        }
    }
}
