<?php

/*
 * Usefule for sending messages between pages
 */
trait Messages
{
    protected function setMessage(string $msg, int $type = 0)
    {
        if (! $type) {
            $aMessageTypes = $this->constMessageTypes();
            $type = $aMessageTypes['info'];
        }
        $_SESSION['_messages'][$msg] = $type;
    }
    
    protected function setErrorMessage(string $msg)
    {
        $aMessageTypes = $this->constMessageTypes();
        $this->setMessage($msg, $aMessageTypes['error']);
    }
    
    protected function setDebugMessage(string $msg)
    {
        $aMessageTypes = $this->constMessageTypes();
        $this->setMessage($msg, $aMessageTypes['debug']);
    }
    
    protected function getMessages(bool $clear = true) :array
    {
        $aMessageTypes = $this->constMessageTypes();
        $msgReturn = [];
        foreach ($_SESSION['_messages'] as $msg => $type) {
            if ($type === $aMessageTypes['info'] || $type === $aMessageTypes['error']) {
                $msgReturn[$msg] = $type;
                if ($clear) {
                    unset($_SESSION['_messages'][$msg]);
                }
            }
        }
        
        return $msgReturn;
    }
    
    protected function getAllMessages(bool $clear = true) :array
    {
        $msg = (isset($_SESSION['_messages'])) ? $_SESSION['_messages'] : [];
        if ($clear) {
            unset($_SESSION['_messages']);
        }
        return $msg;
    }
    
    protected function getDebugMessages(bool $clear = true) :array
    {
        $aMessageTypes = $this->constMessageTypes();
        $msgReturn = [];
        foreach ($_SESSION['_messages'] as $msg => $type) {
            if ($type === $aMessageTypes['debug']) {
                $msgReturn[$msg] = $type;
                if ($clear) {
                    unset($_SESSION['_messages'][$msg]);
                }
            }
        }
        
        return $msgReturn;
    }
    
    public function constMessageTypes() :array
    {
        return [
            'info'  => 1,
            'error' => 2,
            'debug' => 3
        ];
    }
}