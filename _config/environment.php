<?php
// filesystem-specific
define('BASE_DIR', 				'/var/www/mvc');
define('HTTP_PROTOCOL', 		'http://');
define('HTTP', 					HTTP_PROTOCOL . 'www.mvc.ro');
define('HTTP_MAIN', 			HTTP);
define('HTTPS_MAIN', 			HTTP);

// local, dev or live environments
define('ENVIRONMENT',			'local');

// db-specific
define('DB_HOST',				'localhost');
define('DB_USER',				'root');
define('DB_PASS',				'qwqwqw');
define('DB_DATABASE',			'mvc');
?>