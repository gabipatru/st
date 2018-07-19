<?php
class controller_admin_cache extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Cache'), href_admin('cache/list_cache'));
        
        $this->View->assign('menu', 'cache');
    }
    
    function list_cache() {
        $this->View->addSEOParams($this->__('List Cache Items :: Admin'), '', '');
    }
    
    function memcached() {
        $FV = new FormValidation(array(
            'rules' => array(
                'memcached_key' => 'required'
            ),
            'messages' => array(
                'memcached_key' => __('Please specify the memcached key you want to delete')
            )
        ));
        
        $memcache = Mcache::getSingleton();
        $aMemcacheStats = current($memcache->getStats());
        Mcache::prettyStats($aMemcacheStats);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Memcached'), MVC_ACTION_URL);

        $this->View->assign_by_ref('aMemcacheStats', $aMemcacheStats);
        $this->View->assign('FV', $FV);
        
        $this->View->addSEOParams($this->__('Memcached :: Admin'), '', '');
    }
    
    function flush_memcached() {
        $FV = new FormValidation(array(
            'rules' => array(
                'memcached_key' => 'required'
            ),
            'messages' => array(
                'memcached_key' => __('Please specify the memcached key you want to delete')
            )
        ));
        
        $validate = $FV->validate();
        if ($this->isPOST()) {
            try {
                if (!$validate) {
                    throw new Exception(__('Please make sure you filled all the required fields'));
                }
                if (!securityCheckToken(filter_post('token', 'string'))) {
                    throw new Exception(__('The page delay was too long'));
                }
                
                $key = $this->filterPOST('memcached_key', 'string');
                
                $memcache = Mcache::getSingleton();
                $memcache->delete($key);
                
                $this->setMessage($this->___('Key %s was deleted !', $key));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $this->redirect(href_admin('cache/memcached'));
    }
    
    function flush_all_memcached() {
        if ($this->isPOST()) {
            try {
                if (!securityCheckToken(filter_post('token', 'string'))) {
                    throw new Exception(__('The page delay was too long'));
                }
                
                $memcache = Mcache::getSingleton();
                $memcache->flush();
                
                $this->setMessage(__('Memcached keys flushed'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $this->redirect(href_admin('cache/memcached'));
    }
}