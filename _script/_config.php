<?php
require_once(__DIR__ . '/../_config/paths.php');

require_once(CONFIG_DIR . '/constants.php');

require_once(TRAITS_DIR . '/Singleton.trait.php');
require_once(TRAITS_DIR . '/Messages.trait.php');
require_once(TRAITS_DIR . '/Translation.trait.php');

require_once(CLASSES_DIR . '/SetterGetter.php');
require_once(CLASSES_DIR . '/Collection.php');
require_once(CLASSES_DIR . '/db.php');

require_once(FUNCTIONS_DIR . '/html_functions.php');

require_once(SCRIPT_DIR . '/_abstractcron.php');
require_once(SCRIPT_DIR . '/_abstractscript.php');

if (! defined('DEBUGGER_AGENT')) {
    define('DEBUGGER_AGENT', 0);
}
