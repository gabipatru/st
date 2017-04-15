<?php
class controller_admin_config {
    function _prehook() {
        mvc::setDecorations('admin');
        mvc::skipBundles(true);
    
        mvc::addCSS('/bundle-admin.css');
        mvc::addJS('/bundle-admin.js');
        
        mvc::assign('menu', 'config');
    }
    
    function _posthook() {
        $msg = message_get();
        mvc::assign_by_ref('_MESSAGES', $msg);
    }
    
    function list_items() {
        // this is the config we will display
        $configName = filter_get('name', 'string');
        
        $filters = array();
        $options = array();
        $oConfig = new Config();
        $oConfigCollection = $oConfig->Get($filters, $options);
        
        $aSortedConfig = $oConfig->sortData($oConfigCollection);
        
        mvc::assign_by_ref('aConfig', $aSortedConfig);
        mvc::assign('configName', $configName);
    }
    
    function save_all() {
        $aConfigIds = $_POST['config_ids'];
        $configName = filter_post('configName', 'string');
        
        try {
            if (!is_array($aConfigIds)) {
                throw new Exception('Incorrect input of config ids!');
            }
            
            db::startTransaction();
            
            $oConfig = new Config();
            foreach ($aConfigIds as $configId) {
                $configValue = filter_post('config'.$configId, 'clean_html');
                
                $oItem = new SetterGetter();
                $oItem->setValue($configValue);
                
                $r = $oConfig->Edit($configId, $oItem);
                if (!$r) {
                    throw new Exception('Error whilesaving one of the config values');
                }
            }
            
            db::commitTransaction();
            
            // clear config values from memcache
            $Memcache = Mcache::getInstance();
            $Memcache->delete(Config::MEMCACHE_KEY);
        }
        catch (Exception $e) {
            message_set_error($e->getMessage());
            db::rollbackTransaction();
            http_redir(href_admin('config/list_items') . '?name='.$configName);
        }
        
        message_set('All items were saved');
        http_redir(href_admin('config/list_items') . '?name='.$configName);
    }
    
    function add() {
        $FV = new FormValidation(array(
            'rules' => array(
                'path'  => 'required',
                'value' => ''
            ),
            'messages' => array(
                'path'  => 'Please specify the config path',
            )
        ));
        
        $validateResult = $FV->validate();
        if (isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception('Please make sure you filled all mandatory values');
                }
                
                $path  = filter_post('path', 'clean_html');
                $value = filter_post('value', 'clean_html');
                
                if (count(explode('/', trim($path, '/'))) != 3) {
                    throw new Exception('You did not write the config properly');
                }
                
                // check if the path exists
                $filters = array('path' => $path);
                $options = array();
                $oConfig = new Config();
                $oConfigCollection = $oConfig->Get($filters, $options);
                if (count($oConfigCollection) > 0) {
                    throw new Exception('A config with that path already exists');
                }
                
                // add to database
                $oItem = new SetterGetter();
                $oItem->setPath($path);
                $oItem->setValue($value);
                $r = $oConfig->Add($oItem);
                if (!$r) {
                    message_set_error('An error occurred when adding to the database');
                    http_redir(MVC_ACTION_URL);
                }
                
                message_set('Config added successfully');
                http_redir(MVC_MODULE_URL . '/list_items.html');
            }
            catch (Exception $e) {
                message_set_error($e->getMessage());
            }
        }
        
        mvc::assign('FV', $FV);
    }
    
}