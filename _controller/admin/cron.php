<?php

class controller_admin_cron extends ControllerAdminModel
{
    function _prehook() {
        parent::_prehook();

        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Cron'), href_admin('cron/list_items'));

        $this->View->assign('menu', 'cron');
    }

    ###############################################################################
    ## LIST ITEMS PAGE
    ###############################################################################
    function list_crons()
    {
        $search     = $this->filterGET('search', 'string');
        $sort       = $this->filterGET('sort', 'string');
        $sort_crit  = $this->filterGET('sort_crit', 'set[asc,desc]');

        $GF = new GridFilters([
            'status' => [
                'default' => false,
                'valid_values' => [ Cron::CRON_ENABLED, Cron::CRON_DISABLED ]
            ]
        ]);

        $oCron = new Cron();

        // get the crons
        $filters = $GF->filters();
        $options = [
            'search' => $search,
            'search_fields' => [ 'script' ],
            'order_field' => $sort,
            'order_type' => $sort_crit
        ];
        $collectionCron = $oCron->Get($filters, $options);

        $this->View->assign('collectionCron', $collectionCron);
        $this->View->assign('search', $search);
        $this->View->assign('GF', $GF);
        $this->View->assign('sort', $sort);
        $this->View->assign('sort_crit', $sort_crit);

        $this->View->addSEOParams($this->__('List Cron Items :: Admin'), '', '');
    }

    ###############################################################################
    ## LIST CRON RUNS PAGE
    ###############################################################################
    function list_run()
    {
        $page       = $this->filterGET('page', 'int|min[1]');
        $sort       = $this->filterGET('sort', 'string');
        $sort_crit  = $this->filterGET('sort_crit', 'set[asc,desc]');

        $perPage = Config::configByPath(Pagination::PER_PAGE_KEY);

        $GF = new GridFilters([
            'cron_id' => [
                'default' => false,
                'valid_values' => [ ]
            ]
        ]);

        $oCronRun = new CronRun();

        // get the cron runs
        $filters = $GF->filters();
        $options = [
            'page'          => $page,
            'per_page'      => $perPage,
            'search_fields' => [ 'script' ],
            'order_field'   => $sort,
            'order_type'    => $sort_crit
        ];
        $collectionCron = $oCronRun->Get($filters, $options);

        $oPagination = new Pagination();
        $oPagination->setUrl(MVC_ACTION_URL .'?'. $GF->GFHref(false, true, true));
        $oPagination->setPage($page);
        $oPagination->setPerPage($perPage);
        $oPagination->setItemsNo($collectionCron->getItemsNo());
        $oPagination->simple();

        $this->View->assign('collectionCron', $collectionCron);
        $this->View->assign('oPagination', $oPagination);
        $this->View->assign('GF', $GF);
        $this->View->assign('sort', $sort);
        $this->View->assign('sort_crit', $sort_crit);

        $this->View->addSEOParams($this->__('List Cron Run Items :: Admin'), '', '');
    }

    ###############################################################################
    ## EDIT A SCRIPT VIA AJAX
    ###############################################################################
    function ajax_edit_cron()
    {
        $cronId         = $this->filterPOST('cron_id', 'int');
        $newStatus      = $this->filterPOST('new_status', 'string');
        $newInterval    = $this->filterPOST('new_interval', 'int');
        $sToken         = $this->filterPOST('token', 'string');

        try {
            if (!$cronId || !$newStatus || !$newInterval) {
                throw new Exception($this->__('Some parameters are missing'));
            }
            if (!$this->securityCheckToken($sToken)) {
                throw new Exception($this->__('The page delay was too long'));
            }

            $oItem = new SetterGetter();
            $oItem->setInterval($newInterval);
            $oItem->setStatus($newStatus);

            $oCron = new Cron();
            $r = $oCron->Edit($cronId, $oItem);
            if (! $r) {
                throw new Exception($this->__('Error while saving to the database'));
            }

            $this->JsonSuccess();
        } catch (Exception $e) {
            $this->JsonError($e->getMessage());
        }

        exit;
    }
}
