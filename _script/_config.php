<?php
require_once(__DIR__ . '/../_config/paths.php');

require_once(CLASSES_DIR . '/SetterGetter.php');
require_once(CLASSES_DIR . '/Collection.php');
require_once(CLASSES_DIR . '/db.php');

require_once(FUNCTIONS_DIR . '/email.php');
require_once(FUNCTIONS_DIR . '/log.php');
require_once(FUNCTIONS_DIR . '/html_functions.php');

require_once(SCRIPT_DIR . '/_abstractcron.php');

define('DEBUGGER_AGENT', 1);