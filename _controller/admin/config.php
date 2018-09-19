<?php
class controller_admin_config extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
        
        $this->View->assign('menu', 'config');
    }
    
    function list_items() {
        // this is the config we will display
        $configName = $this->filterGET('name', 'string');
        
        $filters = array();
        $options = array();
        $oConfig = new Config();
        $oConfigCollection = $oConfig->Get($filters, $options);
        
        $aSortedConfig = $oConfig->sortData($oConfigCollection);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Config'), MVC_ACTION_URL);
        if ($configName) {
            $Breadcrumbs->Add($configName, CURRENT_URL);
        }
        
        $this->View->assign_by_ref('aConfig', $aSortedConfig);
        $this->View->assign('configName', $configName);
        
        $this->View->addSEOParams($this->__('Config List :: Admin'), '', '');
    }
    
    function save_all() {
        $aConfigIds = $this->filterPOST('config_ids', 'array');
        $configName = $this->filterPOST('configName', 'string');
        
        try {
            if (!is_array($aConfigIds)) {
                throw new Exception($this->__('Incorrect input of config ids!'));
            }
            if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            
            $this->db->startTransaction();
            
            $oConfig = new Config();
            foreach ($aConfigIds as $configId) {
                $configValue = $this->filterPOST('config'.$configId, 'clean_html');

                $oItem = new SetterGetter();
                $oItem->setValue($configValue);
                
                $r = $oConfig->Edit($configId, $oItem);
                if (!$r) {
                    throw new Exception($this->__('Error while saving one of the config values'));
                }
            }
            
            $this->db->commitTransaction();
            
            // clear config values from memcache
            $Memcache = Mcache::getSingleton();
            $Memcache->delete(Config::MEMCACHE_KEY);
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $this->db->rollbackTransaction();
            $this->redirect(href_admin('config/list_items') . '?name='.$configName);
        }
        
        $this->setMessage($this->__('All items were saved'));
        $this->redirect(href_admin('config/list_items') . '?name='.$configName);
    }
    
    function add() {
        $FV = new FormValidation(array(
            'rules' => array(
                'path'  => 'required',
                'type'  => 'required',
                'value' => ''
            ),
            'messages' => array(
                'path'  => $this->__('Please specify the config path'),
                'type'  => $this->__('Please select a config type')
            )
        ));
        
        if ($this->isPOST()) {
            try {
                if (! $this->validate($FV)) {
                    throw new Exception($this->__('Please make sure you filled all mandatory values'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                $path  = $this->filterPOST('path', 'clean_html');
                $value = $this->filterPOST('value', 'clean_html');
                $type  = $this->filterPOST('type', 'string');
                
                if (count(explode('/', trim($path, '/'))) != 3) {
                    throw new Exception($this->__('You did not write the config properly'));
                }
                
                // check if the path exists
                $filters = array('path' => $path);
                $options = array();
                $oConfig = new Config();
                $oConfigCollection = $oConfig->Get($filters, $options);
                if (count($oConfigCollection) > 0) {
                    throw new Exception($this->__('A config with that path already exists'));
                }
                
                // add to database
                $oItem = new SetterGetter();
                $oItem->setPath($path);
                $oItem->setValue($value);
                $oItem->setType($type);
                $r = $oConfig->Add($oItem);
                if (!$r) {
                    $this->setErrorMessage($this->__('Error while saving to the database'));
                    $this->redirect(MVC_ACTION_URL);
                }
                
                $this->setMessage($this->__('Config added successfully'));
                $this->redirect(MVC_MODULE_URL . '/list_items.html');
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $this->View->assign('FV', $FV);
        
        $this->View->addSEOParams($this->__('Add New Config :: Admin'), '', '');
    }
    
}