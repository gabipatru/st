<?php
class controller_admin_dashboard extends ControllerAdminModel {
    
    function _prehook() {
        parent::_prehook();
        
        mvc::assign('menu', 'dashboard');
    }
    
    function stats() {
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add('Dashboard', MVC_ACTION_URL);
    }
}