<?php
class controller_admin_config {
    function _prehook() {
        mvc::setDecorations('admin');
        mvc::skipBundles(true);
    
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
    }
    
    function _posthook() {
        mvc::assign('_MESSAGES', message_get());
    }
    
    function list_items() {
        // this is the config we will display
        $configName = filter_get('name', 'string');
        
        $filters = array();
        $options = array();
        $oConfig = new Config();
        list($data, $nrItems, $maxPage) = $oConfig->Get($filters, $options);
        
        $aSortedConfig = $oConfig->sortData($data);
        
        mvc::assign_by_ref('aConfig', $aSortedConfig);
        mvc::assign('configName', $configName);
    }
    
    function add() {
        $FV = new FormValidation(array(
            'rules' => array(
                'path'  => 'required',
                'value' => ''
            ),
            'messages' => array(
                'path'  => 'Please specify the config path'
            )
        ));
        
        if ($FV->validate()) {
            $path  = filter_post('path', 'clean_html');
            $value = filter_post('value', 'clean_html');
            
            // check if the path exists
            $filters = array('path' => $path);
            $options = array();
            $oConfig = new Config();
            list($data, $nrItems, $maxPage) = $oConfig->Get($filters, $options);
            if ($nrItems > 0) {
                message_set_error('A config with that path already exists');
            }
            else {
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
                http_redir(MVC_MODULE_URL . '/list_items.html');
            }
        }
        
        mvc::assign('FV', $FV);
    }
    
}