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

    ###############################################################################
    ## ELASTICSEARCH PAGE
    ###############################################################################
    function elasticsearch()
    {
        $oElastic = ElasticSearch::getSingleton();

        try {
            // get elasticsearch stats
            $indexInfo = $oElastic->elasticStats();

            $indexInfo = json_decode($indexInfo, true);

            $aElasticData = [];
            foreach ($indexInfo as $index) {
                // prepare the data to be displayed
                $data = [];
                if (isset($index['health'])) {
                    $data['status'] = $index['health'];
                }
                if (isset($index['index'])) {
                    $data['name'] = $index['index'];
                }
                if (isset($index['docs.count'])) {
                    $data['docs_no'] = $index['docs.count'];
                }
                if (isset($index['store.size'])) {
                    $data['storage'] = $index['store.size'];
                }

                $aElasticData[] = $data;
            }

            $this->View->assign_by_ref('aElasticData', $aElasticData);
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
        }
    }

    ###############################################################################
    ## DELETE INDEX FROM ELASTICSEARCH
    ###############################################################################
    public function delete_elastic_index()
    {
        $oElastic = ElasticSearch::getSingleton();
        $indexName = $this->filterGET('index_name', 'string');

        try {
            if (! $this->securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (! $indexName) {
                throw new Exception($this->__('Index name is missing'));
            }

            $r = $oElastic->DeleteIndex($indexName);
            if ($r === false) {
                throw new Exception($this->__('Error while deleting index from Elasticsearch'));
            }

            $this->setMessage($this->__('Index was deleted'));
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
        }

        $this->redirect(href_admin('cache/elasticsearch'));
    }

    ###############################################################################
    ## REINDEX THE DATABASE CONTENTS INTO ELASTICSEARCH
    ###############################################################################
    public function reindex_elastic()
    {
        $referrer = $this->filterGET('referrer', 'string');
        if (! $referrer) {
            $referrer = $this->hrefAdmin('cache/list_cache');
        }

        $pathToScript = SCRIPT_DIR . '/script.php IndexDataInElasticsearch 3';

        // run the script in background
        shell_exec('php ' . $pathToScript . ' &');

        $this->setMessage($this->__('Reindexing of data in Elasticsearch started'));
        $this->redirect($referrer);
    }
}
