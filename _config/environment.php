<?php
// filesystem-specific
define('BASE_DIR', 				getenv('WEBSITE_PATH'));
define('HTTP_PROTOCOL', 		'http://');
define('HTTP', 					HTTP_PROTOCOL . 'www.st.ro');
define('HTTP_MAIN', 			HTTP);
define('HTTPS_MAIN', 			HTTP);

// local, dev or live environments
define('ENVIRONMENT',			'local');

// db-specific
define('DB_HOST',				'localhost');
define('DB_USER',				'st');
define('DB_PASS',				'qwqwqwqw');
define('DB_DATABASE',			'mvc');

// memcached-specific
define('MEM_HOST',              '127.0.0.1');
define('MEM_PORT',              11211);
define('MEM_EXPIRE_TIME',       14400);             // 4 hours

//SMTP settings
define('SMTP_HOST', 'smtp.mailtrap.io');
define('SMTP_PORT', '2525');
define('SMTP_USERNAME', '89ee358b9ac8f5');
define('SMTP_PASSWORD', 'a1ec8e63feb328');

?>