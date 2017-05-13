<?php
require_once(__DIR__ . '/../_config/paths.php');

require_once(CLASSES_DIR . '/SetterGetter.php');
require_once(CLASSES_DIR . '/Collection.php');
require_once(CLASSES_DIR . '/db.php');

require_once(CLASSES_DIR . '/db_data/_db_data_model.php');
require_once(CLASSES_DIR . '/db_data/_db_data.php');
require_once(CLASSES_DIR . '/db_data/Config.php');
require_once(CLASSES_DIR . '/db_data/EmailLog.php');
require_once(CLASSES_DIR . '/db_data/UserConfirmation.php');

require_once(FUNCTIONS_DIR . '/email.php');
require_once(FUNCTIONS_DIR . '/log.php');

require_once(SCRIPT_DIR . '/_abstractcron.php');

define('DEBUGGER_AGENT', 1);