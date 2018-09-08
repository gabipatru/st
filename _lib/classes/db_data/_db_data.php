<?php

/*
 * This class implements the abstract methods of daDataModel so you won't have to use boilerplate code
 * every time you extend that method
 */

class DbData extends dbDataModel 
{
    const ALLOW_DELETE_KEY = '/Website/Database/Delete permitted';
    
    protected function onBeforeAdd($oItem) {
        return true;
    }
    
    protected function onBeforeDelete($iId) {
        return true;
    }
    
    protected function onBeforeEdit($iId, $oItem) {
        return true;
    }
    
    protected function onBeforeGet($filters, $options) {
        return true;
    }
    
    protected function onDelete($iId) {
        return true;
    }
    
    protected function onEdit($iId, $res) {
        return true;
    }
    
    protected function onGet(Collection $oCollection): bool 
    {
        return true;
    }
    
    protected function onSetStatus($iId) {
        return true;
    }
}