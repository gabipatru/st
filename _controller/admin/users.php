<?php

class controller_admin_users extends ControllerAdminModel {

    function _prehook() {
        parent::_prehook();
    
        $this->View->assign('menu', 'users');
    }

    ###############################################################################
    ## LIST USERS PAGE
    ###############################################################################
    function list_users() {
        $page       = $this->filterGET('page', 'int|min[1]');
        $search     = $this->filterGET('search', 'string');
        $sort       = $this->filterGET('sort', 'string');
        $sort_crit  = $this->filterGET('sort_crit', 'set[asc,desc]');
        
        $perPage = Config::configByPath(Pagination::PER_PAGE_KEY);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Users'), MVC_ACTION_URL);
        
        $GF = new GridFilters(array(
            'status' => array(
                'default' => false,
                'valid_values' => array(User::STATUS_ACTIVE, User::STATUS_NEW, User::STATUS_BANNED)
            )
        ));
        
        // fetch users
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
        
        // fetch user groups
        $oUserGroupModel = new UserGroup();
        $oUserGroupCollection = $oUserGroupModel->Get();
        
        $oPagination = new Pagination();
        $oPagination->setUrl(MVC_ACTION_URL.'?' . $GF->GFHref(false, true, true));
        $oPagination->setPage($page);
        $oPagination->setPerPage($perPage);
        $oPagination->setItemsNo($oUserCol->getItemsNo());
        $oPagination->simple();
        
        $this->View->assign('oUserCol', $oUserCol);
        $this->View->assign('oUserGroupCollection', $oUserGroupCollection);
        $this->View->assign('oPagination', $oPagination);
        $this->View->assign('search', $search);
        $this->View->assign('GF', $GF);
        $this->View->assign('sort', $sort);
        $this->View->assign('sort_crit', $sort_crit);
        
        $this->View->addSEOParams($this->__('Users List :: Admin'), '', '');
    }

    ###############################################################################
    ## CHANGE STATUS ENDPOINT
    ###############################################################################
    function ajax_change_status() {
        $userId         = $this->filterPOST('user_id', 'int');
        $newStatus      = $this->filterPOST('new_status', 'string');
        $newUserGroupId = $this->filterPOST('new_user_group_id', 'int');
        $sToken         = $this->filterPOST('token', 'string');
        
        try {
            if (!$userId || !$newStatus) {
                throw new Exception($this->__('User id or new status are missing'));
            }
            
            if (!$this->securityCheckToken($sToken)) {
                throw new Exception($this->__('The page delay was too long'));
            }
            
            $oItem = new SetterGetter();
            $oItem->setUserGroupId($newUserGroupId);
            $oItem->setStatus($newStatus);
            
            $oUser = new User();
            $r = $oUser->Edit($userId, $oItem);
            if (!$r) {
                throw new Exception($this->__('Error while saving to the database'));
            }
            
            $this->JsonSuccess();
        }
        catch (Exception $e) {
            $this->JsonError($e->getMessage());
        }
        
        exit;
    }
}