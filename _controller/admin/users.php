<?php
class controller_admin_users extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
    
        mvc::assign('menu', 'users');
    }
    
    function list_users() {
        $page       = filter_get('page', 'int|min[1]');
        $search     = filter_get('search', 'string');
        $sort       = filter_get('sort', 'string');
        $sort_crit  = filter_get('sort_crit', 'set[asc,desc]');
        
        $perPage = Config::configByPath(Pagination::PER_PAGE_KEY);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add(__('Users'), MVC_ACTION_URL);
        
        $GF = new GridFilters(array(
            'status' => array(
                'default' => false,
                'valid_values' => array(User::STATUS_ACTIVE, User::STATUS_NEW, User::STATUS_BANNED)
            )
        ));
        
        $oUser = new User();
        $filters = $GF->filters();
        $options = array(
                'page' => $page, 
                'per_page' => $perPage,
                'search' => $search,
                'search_fields' => array('username', 'email', 'first_name', 'last_name'),
                'order_field' => $sort,
                'order_type' => $sort_crit
        );
        $oUserCol = $oUser->Get($filters, $options);
        
        $oPagination = new Pagination();
        $oPagination->setUrl(MVC_ACTION_URL.'?' . $GF->GFHref(false, true, true));
        $oPagination->setPage($page);
        $oPagination->setPerPage($perPage);
        $oPagination->setItemsNo($oUserCol->getItemsNo());
        $oPagination->simple();
        
        mvc::assign('oUserCol', $oUserCol);
        mvc::assign('oPagination', $oPagination);
        mvc::assign('search', $search);
        mvc::assign('GF', $GF);
        mvc::assign('sort', $sort);
        mvc::assign('sort_crit', $sort_crit);
    }
    
    function ajax_change_status() {
        $userId     = filter_post('user_id', 'int');
        $newStatus  = filter_post('new_status', 'string');
        $sToken     = filter_post('token', 'string');
        sleep(20);
        try {
            if (!$userId || !$newStatus) {
                throw new Exception();
            }
            
            if (!securityCheckToken($sToken)) {
                throw new Exception();
            }
            
            $oItem = new SetterGetter();
            $oItem->setStatus($newStatus);
            
            $oUser = new User();
            $r = $oUser->Edit($userId, $oItem);
            if (!$r) {
                throw new Exception();
            }
            
            ajax_json::success();
            ajax_json::output_json();
        }
        catch (Exception $e) {
            ajax_json::error();
            ajax_json::output_json();
        }
        
        exit;
    }
}