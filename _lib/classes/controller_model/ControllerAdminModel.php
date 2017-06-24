<?php
/*
 * You can extend admin controllers from this one
 */
class ControllerAdminModel {
    function _prehook() {
        if (!User::isLoggedIn()) {
            http_redir(href_website('user/login', CURRENT_URL));
        }
        
        $theUser = User::theUser();
        if (!$theUser->getIsAdmin()) {
            http_redir(href_website('user/login', CURRENT_URL));
        }
        
        mvc::setDecorations('admin');
    
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
        
        $oTranslations = Translations::getSingleton();
        $oTranslations->setModule('admin');

        $oUser = User::theUser();
        mvc::assign('userName', $oUser->getFirstName() . ' ' . $oUser->getLastName());
    }
    
    function _posthook() {

    }
}