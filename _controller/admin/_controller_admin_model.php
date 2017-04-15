<?php
/*
 * You can extend admin controllers from this one
 */
class ControllerAdminModel {
    function _prehook() {
        mvc::setDecorations('admin');
        mvc::skipBundles(true);
    
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
    }
    
    function _posthook() {
        $msg = message_get();
        mvc::assign_by_ref('_MESSAGES', $msg);
    }
}