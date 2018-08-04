<?php
self::$View->assign('_MESSAGES', message_get());
self::$View->assign('https', false);

// close memcached connection
$Memcached = Mcache::getSingleton();
$Memcached->quit();

// measure execution time
$timeEnd = microtime(true);
self::$View->assign('executionTime', ($timeEnd - $timeStart) * 1000);
?>