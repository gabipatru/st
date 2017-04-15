<?php
class controller_admin_dashboard {
    
    function _prehook() {
        mvc::setDecorations('admin');
        mvc::skipBundles(true);
        
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
        
        mvc::assign('menu', 'dashboard');
    }
    
    function stats() {
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add('Dashboard', MVC_ACTION_URL);
    }
}