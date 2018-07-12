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
require_once(FUNCTIONS_DIR . '/email.php');

securityUpdateToken();

// load common translations
$language = (isset($_COOKIE[Translations::COOKIE_NAME]) ? $_COOKIE[Translations::COOKIE_NAME] : DEFAULT_TRANSLATION_LANGUAGE);
$oTranslations = Translations::getSingleton();
$oTranslations->setLanguage($language);
$oTranslations->setModule('common');
self::$View->assign('oTranslations', $oTranslations);

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
$aConfigIndex = $oConfig->indexByPath();

$oRegsitry = Registry::getSingleton();
$oRegsitry->set(Config::REGISTRY_KEY, $oConfigCollection);
$oRegsitry->set(Config::REGISTRY_KEY_PATH, $aConfigIndex);

// set up the logged in user
if (User::isLoggedIn()) {
    $oRegsitry->set(User::REGISTRY_KEY, unserialize($_SESSION['user_data']));
}

//set up cachebuster
$busterJson = file_get_contents(STATIC_DIR . '/busters.json');
$aCacheBuster = json_decode($busterJson, true);
self::$View->addCacheBuster($aCacheBuster);

// set up Content Security Policy
$csp = Config::configByPath('/Website/Security/Content Security Policy');
header("Content-Security-Policy: $csp");
?>