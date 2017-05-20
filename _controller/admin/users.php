<?php
class controller_admin_users extends ControllerAdminModel {
    function _prehook() {
        parent::_prehook();
    
        mvc::assign('menu', 'users');
    }
    
    function list_users() {
        $page       = filter_get('page', 'int|min[1]');
        $search     = filter_get('search', 'string');
        
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
                'search_fields' => array('username', 'email', 'first_name', 'last_name')
        );
        $oUserCol = $oUser->Get($filters, $options);
        
        $oPagination = new Pagination();
        $oPagination->setUrl(MVC_MODULE_URL.'/list_users.html?' . $GF->GFHref());
        $oPagination->setPage($page);
        $oPagination->setPerPage($perPage);
        $oPagination->setItemsNo($oUserCol->getItemsNo());
        $oPagination->simple();
        
        mvc::assign('oUserCol', $oUserCol);
        mvc::assign('oPagination', $oPagination);
        mvc::assign('search', $search);
        mvc::assign('GF', $GF);
    }
}