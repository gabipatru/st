<?php
class controller_admin_email extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
    
        mvc::assign('menu', 'email');
    }
    
    function list_menu() {
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Email'), MVC_ACTION_URL);
    }
    
    function email_queue() {
        $page       = filter_get('page', 'int|min[1]');
        $search     = filter_get('search', 'string');
        $sort       = filter_get('sort', 'string');
        $sort_crit  = filter_get('sort_crit', 'set[asc,desc]');
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Email'), MVC_MODULE_URL . '/list_menu.html');
        $Breadcrumbs->Add(__('Email queue'), MVC_ACTION_URL);
        
        $perPage = Config::configByPath(Pagination::PER_PAGE_KEY);
        
        $GF = new GridFilters(array(
            'status' => array(
                'default' => false,
                'valid_values' => array(EmailQueue::STATUS_NOT_SENT, EmailQueue::STATUS_SENT)
            )
        ));
        
        $oEmailQueue = new EmailQueue();
        $filters = $GF->filters();
        $options = array(
            'page' => $page,
            'per_page' => $perPage,
            'search' => $search,
            'search_fields' => array('to', 'subject'),
            'order_field' => $sort,
            'order_type' => $sort_crit
        );
        
        // fetch from database
        $oEmailCollection = $oEmailQueue->Get($filters, $options);
        
        $oPagination = new Pagination();
        $oPagination->setUrl(MVC_ACTION_URL.'?' . $GF->GFHref(false, true, true));
        $oPagination->setPage($page);
        $oPagination->setPerPage($perPage);
        $oPagination->setItemsNo($oEmailCollection->getItemsNo());
        $oPagination->simple();
        
        mvc::assign('oCollection', $oEmailCollection);
        mvc::assign('oPagination', $oPagination);
        mvc::assign('search', $search);
        mvc::assign('GF', $GF);
        mvc::assign('sort', $sort);
        mvc::assign('sort_crit', $sort_crit);
    }
    
    function email_log() {
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Email'), MVC_MODULE_URL . '/list_menu.html');
        $Breadcrumbs->Add(__('Email log'), MVC_ACTION_URL);
    }
}