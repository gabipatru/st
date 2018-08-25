<?php
/*
 * You can extend admin controllers from this one
 */
class ControllerAdminModel extends AbstractController {
    function _prehook() {
        // check if the user is logged in
        if (!$this->isLoggedIn()) {
            $this->redirect(href_website('user/login', CURRENT_URL));
        }
        
        // check if the logged in user is admin
        $theUser = User::theUser();
        if (!$theUser->getIsAdmin()) {
            $this->redirect(href_website('user/login', CURRENT_URL));
        }
        
        // check if the logged in user has permissions to perform the action
        $oAcl = Acl::getSingleton();
        $taskId = $oAcl->getTaskId(MVC_MODULE);
        if (! $oAcl->checkPermission($taskId, User::theId())) {
            $this->setErrorMessage($this->__("You don't have permission to access that page!"));
            $this->redirect(href_website('website/homepage'));
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