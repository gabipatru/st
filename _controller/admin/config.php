<?php
class controller_admin_config {
    function _prehook() {
        mvc::setDecorations('admin');
        mvc::skipBundles(true);
    
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
    }
    
    function _posthook() {
        mvc::assign_by_ref('_MESSAGES', message_get());
    }
    
    function list_items() {
        
    }
    
    function save() {
        
    }
    
    function add() {
        $FV = new FormValidation(array(
            'rules' => array(
                'path'  => 'required'
            ),
            'messages' => array(
                'path'  => 'Please specify the config path'
            )
        ));
        
        if ($FV->validate()) {
            $path  = filter_post('path', 'string');
            $value = filter_post('value', 'string');
            
            // check if the path exists
            $filters = array('path' => $path);
            $options = array();
            $oConfig = new Config();
            list($data, $nrItems, $maxPage) = $oConfig->Get($filters, $options);
            if ($nrItems > 0) {
                message_set_error('A config with that path already exists');
                http_redir(MVC_ACTION_URL);
            }
            
            // add to database
            $data = array(
                'path'  => $path,
                'value' => $value
            );
            $r = $oConfig->Add($data);
            if (!$r) {
                message_set_error('An error occurred when adding to the database');
                http_redir(MVC_ACTION_URL);
            }
            
            message_set('Config added successfully');
            http_redir(MVC_MODULE_URL . '/list.html');
        }
        
        mvc::assign('FV', $FV);
    }
    
}