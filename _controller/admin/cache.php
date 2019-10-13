<?php

class controller_admin_cache extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Cache'), href_admin('cache/list_cache'));
        
        $this->View->assign('menu', 'cache');
    }

    ###############################################################################
    ## LIST CACHE PAGE
    ###############################################################################
    function list_cache() {
        $this->View->addSEOParams($this->__('List Cache Items :: Admin'), '', '');
    }

    ###############################################################################
    ## MEMCACHED PAGE
    ###############################################################################
    function memcached() {
        $FV = new FormValidation(array(
            'rules' => array(
                'memcached_key' => 'required'
            ),
            'messages' => array(
                'memcached_key' => $this->__('Please specify the memcached key you want to delete')
            )
        ));
        
        $memcache = Mcache::getSingleton();
        $aMemcacheStats = current($memcache->getStats());
        Mcache::prettyStats($aMemcacheStats);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Memcached'), MVC_ACTION_URL);

        $this->View->assign_by_ref('aMemcacheStats', $aMemcacheStats);
        $this->View->assign('FV', $FV);
        
        $this->View->addSEOParams($this->__('Memcached :: Admin'), '', '');
    }

    ###############################################################################
    ## FLUSH PAGE
    ###############################################################################
    function flush_memcached() {
        $FV = new FormValidation(array(
            'rules' => array(
                'memcached_key' => 'required'
            ),
            'messages' => array(
                'memcached_key' => $this->__('Please specify the memcached key you want to delete')
            )
        ));

        if ($this->isPOST()) {
            try {
                if (! $this->validate($FV)) {
                    throw new Exception($this->__('Please make sure you filled all the required fields'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
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

    ###############################################################################
    ## FLUSH ALL MEMCACHED PAGE
    ###############################################################################
    function flush_all_memcached() {
        if ($this->isPOST()) {
            try {
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                $memcache = Mcache::getSingleton();
                $memcache->flush();
                
                $this->setMessage($this->__('Memcached keys flushed'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $this->redirect(href_admin('cache/memcached'));
    }
}