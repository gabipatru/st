<?php

/**
 * All script run by the script.php will extend this.
 */

namespace Cron;

class AbstractScript extends \SetterGetter
{
    use CronDisplayMsg;

    protected $db;

    public function __construct(\db $db)
    {
        $this->db = $db;
        $this->checkDebugOption();
    }
}
