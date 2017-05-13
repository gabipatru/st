<?php
// get configs that are specific to the server
require_once("environment.php");

define('CONFIG_DIR', 			BASE_DIR.'/_config');
define('CLASSES_DIR', 			BASE_DIR.'/_lib/classes');
define('FUNCTIONS_DIR', 		BASE_DIR.'/_lib/functions');
define('CONTROLLER_DIR',		BASE_DIR.'/_controller');
define('MIGRATIONS_DIR',        BASE_DIR.'/_migrations');
define('SCRIPT_DIR',            BASE_DIR.'/_script');
define('VIEW_DIR', 				BASE_DIR.'/_view');
define('VIEW_INCLUDES_DIR', 	VIEW_DIR.'/_include');
define('DECORATIONS_DIR', 		VIEW_DIR.'/_core/decorations');
define('STATIC_DIR', 			BASE_DIR.'/public_html/_static');
define('CSS_DIR', 				STATIC_DIR.'/css');
define('JS_DIR', 				STATIC_DIR.'/js');
define('FILES_DIR', 			BASE_DIR.'/files');
define('TRANSLATIONS_DIR',      BASE_DIR.'/_translations');
define('EMAIL_VIEW_DIR',		VIEW_DIR.'/_email');
define('EMAIL_DECORATIONS_DIR', EMAIL_VIEW_DIR.'/_core/decorations');

define('HTTP_STATIC', 			HTTP_MAIN.'/_static');
define('HTTPS_STATIC', 			HTTPS_MAIN.'/_static');
define('HTTP_CSS', 				HTTP_STATIC.'/css');
define('HTTPS_CSS', 			HTTPS_STATIC.'/css');
define('HTTP_JS', 				HTTP_STATIC.'/js');
define('HTTPS_JS', 				HTTPS_STATIC.'/js');
define('HTTP_IMAGES', 			HTTP_STATIC.'/images');
define('HTTP_ICONS',			HTTP_IMAGES.'/icons');

define('CURRENT_URL', 			(isset($_SERVER['REQUEST_URI']) ? HTTP . $_SERVER['REQUEST_URI'] : ''));
define('CURRENT_URL_ENCODED',	urlencode(CURRENT_URL));
?>