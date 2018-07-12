<?php
/*
 * You can extend admin controllers from this one
 */
class ControllerAdminModel extends AbstractController {
    function _prehook() {
        if (!User::isLoggedIn()) {
            http_redir(href_website('user/login', CURRENT_URL));
        }
        
        $theUser = User::theUser();
        if (!$theUser->getIsAdmin()) {
            http_redir(href_website('user/login', CURRENT_URL));
        }
        
        $this->View->setDecorations('admin');
    
        $this->View->addCSS('/bundle-admin.css');
        $this->View->addJS('/bundle-admin.js');
        
        $oTranslations = Translations::getSingleton();
        $oTranslations->setModule('admin');

        $oUser = User::theUser();
        $this->View->assign('userName', $oUser->getFirstName() . ' ' . $oUser->getLastName());
    }
    
    function _posthook() {

    }
}