<?php
class controller_admin_email extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
    
        $this->View->assign('menu', 'email');
    }
    
    function list_menu() {
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Email'), MVC_ACTION_URL);
        
        $this->View->addSEOParams($this->__('Email :: Admin'), '', '');
    }
    
    function email_queue() {
        $page       = $this->filterGET('page', 'int|min[1]');
        $search     = $this->filterGET('search', 'string');
        $sort       = $this->filterGET('sort', 'string');
        $sort_crit  = $this->filterGET('sort_crit', 'set[asc,desc]');
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Email'), MVC_MODULE_URL . '/list_menu.html');
        $Breadcrumbs->Add($this->__('Email queue'), MVC_ACTION_URL);
        
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
            'search_fields' => array('too', 'subject'),
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
        
        $this->View->assign('oCollection', $oEmailCollection);
        $this->View->assign('oPagination', $oPagination);
        $this->View->assign('search', $search);
        $this->View->assign('GF', $GF);
        $this->View->assign('sort', $sort);
        $this->View->assign('sort_crit', $sort_crit);
        
        $this->View->addSEOParams($this->__('Email queue :: Admin'), '', '');
    }
    
    function email_log() {
        $page       = $this->filterGET('page', 'int|min[1]');
        $search     = $this->filterGET('search', 'string');
        $sort       = $this->filterGET('sort', 'string');
        $sort_crit  = $this->filterGET('sort_crit', 'set[asc,desc]');
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Email'), MVC_MODULE_URL . '/list_menu.html');
        $Breadcrumbs->Add($this->__('Email log'), MVC_ACTION_URL);
        
        $perPage = Config::configByPath(Pagination::PER_PAGE_KEY);
        
        $GF = new GridFilters(array(
            'status' => array(
                'default' => false,
                'valid_values' => array(EmailQueue::STATUS_NOT_SENT, EmailQueue::STATUS_SENT)
            )
        ));
        
        $oEmailLog = new EmailLog();
        $filters = $GF->filters();
        $options = array(
            'page' => $page,
            'per_page' => $perPage,
            'search' => $search,
            'search_fields' => array('too'),
            'order_field' => $sort,
            'order_type' => $sort_crit
        );
        
        // fetch from database
        $oEmailCollection = $oEmailLog->Get($filters, $options);
        
        $oPagination = new Pagination();
        $oPagination->setUrl(MVC_ACTION_URL.'?' . $GF->GFHref(false, true, true));
        $oPagination->setPage($page);
        $oPagination->setPerPage($perPage);
        $oPagination->setItemsNo($oEmailCollection->getItemsNo());
        $oPagination->simple();
        
        $this->View->assign('oCollection', $oEmailCollection);
        $this->View->assign('oPagination', $oPagination);
        $this->View->assign('search', $search);
        $this->View->assign('GF', $GF);
        $this->View->assign('sort', $sort);
        $this->View->assign('sort_crit', $sort_crit);
        
        $this->View->addSEOParams($this->__('Email Log :: Admin'), '', '');
    }
}