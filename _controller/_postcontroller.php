<?php
mvc::assign('_MESSAGES', message_get());
mvc::assign('https', false);

// close memcached connection
$Memcached = Mcache::getSingleton();
$Memcached->quit();
?>