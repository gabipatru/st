<?php

/*
 * This class will run all the scheduled scripts by calling the script.php for each one.
 * It will run them only if they are scheduled to run.
 */
namespace Cron;

require_once __DIR__ . '/_config.php';
require_once __DIR__ . '/_cron.php';

$Cron = new Cron();
$Cron->run();
