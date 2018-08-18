<?php
$this->View->assign('_MESSAGES', $this->getMessages());
$this->View->assign('https', false);

// close memcached connection
$Memcached = Mcache::getSingleton();
$Memcached->quit();

// memory usage
$memFootprint = memory_get_peak_usage(false);
$this->View->assign('memFootprint', $memFootprint);

// measure execution time
$timeEnd = microtime(true);
$this->View->assign('executionTime', ($timeEnd - $timeStart) * 1000);
?>