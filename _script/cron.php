<?php

/*
 * This class will run all the scheduled scripts by calling the script.php for each one
 */

namespace Cron;

require_once __DIR__ . '/_config.php';
require_once __DIR__ . '/_cron.php';

$Cron = new Cron();
$Cron->run();
