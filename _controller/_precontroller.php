<?php
session_start();
ob_start();

require_once(CONFIG_DIR . '/pages.php');
require_once(CONFIG_DIR . '/debug.php');
require_once(CONFIG_DIR . '/links.php');
require_once(CONFIG_DIR . '/constants.php');

require_once(FUNCTIONS_DIR . '/messages.php');
require_once(FUNCTIONS_DIR . '/http_functions.php');
require_once(FUNCTIONS_DIR . '/html_functions.php');
require_once(FUNCTIONS_DIR . '/filter.php');
require_once(FUNCTIONS_DIR . '/log.php');
require_once(FUNCTIONS_DIR . '/security_token.php');

securityUpdateToken();

// load common translations
$oTranslations = Translations::getSingleton();
$oTranslations->setLanguage('ro_RO');
$oTranslations->setModule('common');

// database connection
try {
    db::connect();
    $oMigration = new Migration();
    $oMigration->runMigrations();
}
catch (Exception $e) {
    die(__("Could not connect to database"));
}

// config setup
$oConfig = new Config();
$oConfigCollection = $oConfig->Get();

$oRegsitry = Registry::getInstance();
$oRegsitry->set(Config::REGISTRY_KEY, $oConfigCollection);

?>