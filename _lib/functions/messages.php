<?php

/*
These functions can be used to send success or error messages between different pages
*/

function message_set($msg, $error = false) {
    $_SESSION['_messages'][$msg] = $error;
}

function message_set_error($msg) {
    message_set($msg, true);
}

function message_get($clear = true) {
    $msg = (isset($_SESSION['_messages'])) ? $_SESSION['_messages'] : array();
    if ($clear) {
        unset($_SESSION['_messages']);
    }
    return $msg;
}

?>