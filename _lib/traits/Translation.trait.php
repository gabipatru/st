<?php

/*
 * Use this trait if you want your class needs to translate messages
 */
trait Translation
{
    protected function __(string $msg) :string
    {
        $oTranslations = Translations::getSingleton();
        return $oTranslations->__($msg);
    }
    
    protected function ___(string $msg, ...$params) :string
    {
        $oTranslations = Translations::getSingleton();
        return $oTranslations->___($msg, $params);
    }
}