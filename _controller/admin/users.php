<?php
class controller_admin_users extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
    
        mvc::assign('menu', 'users');
    }
    
    function list_users() {
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Users'), MVC_ACTION_URL);
        
        $oUser = new User();
        $filters = array();
        $options = array();
        $oUserCol = $oUser->Get($filters, $options);
        
        mvc::assign('oUserCol', $oUserCol);
    }
}