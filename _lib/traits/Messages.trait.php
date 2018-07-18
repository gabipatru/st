<?php

/*
 * Usefule for sending messages between pages
 */
trait Messages
{
    protected function setMessage(string $msg, ?bool $error = false)
    {
        $_SESSION['_messages'][$msg] = $error;
    }
    
    protected function setErrorMessage(string $msg)
    {
        $this->setMessage($msg, true);
    }
    
    protected function getMessages(bool $clear = true) :array
    {
        $msg = (isset($_SESSION['_messages'])) ? $_SESSION['_messages'] : [];
        if ($clear) {
            unset($_SESSION['_messages']);
        }
        return $msg;
    }
}