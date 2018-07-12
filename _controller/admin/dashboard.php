<?php
class controller_admin_dashboard extends ControllerAdminModel {
    
    function _prehook() {
        parent::_prehook();
        
        $this->View->assign('menu', 'dashboard');
    }
    
    function stats() {
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Dashboard'), MVC_ACTION_URL);
    }
}