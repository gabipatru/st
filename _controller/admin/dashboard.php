<?php
class controller_admin_dashboard {
    
    function _prehook() {
        mvc::setDecorations('admin');
        mvc::skipBundles(true);
        
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
    }
    
    function stats() {
        
    }
}