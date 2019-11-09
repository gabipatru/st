<?php

if ($_SERVER['HTTP_USER_AGENT'] == 'd33eecfe935f9f931a8407f87298dc73') {
    define('DEBUGGER_AGENT', 1);
    error_reporting(E_ALL & ~E_STRICT);
    ini_set('display_errors', 1);
} else {
    define('DEBUGGER_AGENT', 0);
}
