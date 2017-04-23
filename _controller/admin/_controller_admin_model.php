<?php
/*
 * You can extend admin controllers from this one
 */
class ControllerAdminModel {
    function _prehook() {
        mvc::setDecorations('admin');
    
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
        
        $oTranslations = Translations::getSingleton();
        $oTranslations->setModule('admin');
    }
    
    function _posthook() {
        $msg = message_get();
        mvc::assign_by_ref('_MESSAGES', $msg);
    }
}