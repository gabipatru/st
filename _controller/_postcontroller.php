<?php
self::$View->assign('_MESSAGES', message_get());
self::$View->assign('https', false);

// close memcached connection
$Memcached = Mcache::getSingleton();
$Memcached->quit();
?>