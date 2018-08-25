<?php

class controller_admin_user_groups extends ControllerAdminModel
{
    function _prehook()
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'users');
    }
    
    // List user groups
    function list_user_groups()
    {
        $oUserGroupModel = new UserGroup();
        
        // get all the categories
        $oGroupsCollection = $oUserGroupModel->Get();
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Users'), href_admin('users/list_users'));
        $Breadcrumbs->Add($this->__('User Groups'), MVC_ACTION_URL);
        
        $this->View->assign('oGroupsCollection', $oGroupsCollection);
        
        $this->View->addSEOParams($this->__('User Groups List :: Admin'), '', '');
    }
    
    // Add / edit a user group
    function edit()
    {
        $userGroupId = $this->filterGET('user_group_id', 'int');
        
        $oUserGroupModel = new UserGroup();
        
        $FV = new FormValidation([
            'rules' => [
                'name'          => 'required',
                'status'        => 'required',
                'description'   => ''
            ],
            'messages' => [
                'name'      => $this->__('Please specify a user group name'),
                'status'    => $this->__('Please select a valid status')
            ]
        ]);
        
        $validateResult = $FV->validate();
        
        if ($this->isPOST()) {
            try {
                if (! $validateResult) {
                    throw new Exception($this->__('Please make sure you filled all mandatory values'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                // filter the values
                $name           = $this->filterPOST('name', 'clean_html');
                $description    = $this->filterPOST('description', 'clean_html');
                $status         = $this->filterPOST('status', 'set[online,offline]');
                
                if (! $userGroupId) {
                    // check if another category with that name exists
                    $filters = [ 'name' => $name ];
                    $oCollection = $oUserGroupModel->Get($filters);
                    if (count($oCollection) > 0) {
                        throw new Exception($this->__('A user group with that name already exists!'));
                    }
                }
                
                // set up the item to be saved
                $oItem = new SetterGetter();
                $oItem->setName($name);
                $oItem->setDescription($description);
                $oItem->setStatus($status);
                
                // save to db
                if (! $userGroupId) {
                    $r = $oUserGroupModel->Add($oItem);
                }
                else {
                    $r = $oUserGroupModel->Edit($userGroupId, $oItem);
                }
                
                // check results
                if (!$r) {
                    throw new Exception($this->__('Error while saving to the database'));
                }
                
                $this->setMessage($this->__('The user group was saved.'));
                $this->redirect(href_admin('user_groups/list'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $oUserGroup = new SetterGetter();
        if ($userGroupId) {
            $filters = [ 'user_group_id' => $userGroupId ];
            $oUserGroup = $oUserGroupModel->singleGet($filters);
            
            $FV->initDefault($oUserGroup);
        }
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Users'), href_admin('users/list_users'));
        $Breadcrumbs->Add($this->__('User Groups'), MVC_ACTION_URL);
        
        if ($userGroupId) {
            $this->View->addSEOParams($this->__('Edit User Groups :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Edit User Groups'), CURRENT_URL);
        }
        else {
            $this->View->addSEOParams($this->__('Add User Groups :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Add User Groups'), CURRENT_URL);
        }
        
        $this->View->assign('FV', $FV);
        $this->View->assign('userGroupId', $userGroupId);
    }
    
    // Delete a user group
    function delete()
    {
        $userGroupId = $this->filterGET('user_group_id', 'int');
        
        try {
            if (! $this->securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (Config::configByPath(DbData::ALLOW_DELETE_KEY) === Config::CONFIG_VALUE_NO) {
                throw new Exception($this->__('Delete not allowed'));
            }
            if (! $userGroupId) {
                throw new Exception($this->__('User Group ID is missing.'));
            }
            
            // delete
            $oUserGroupModel = new UserGroup();
            $r = $oUserGroupModel->Delete($userGroupId);
            if (!$r) {
                throw new Exception($this->__('Error while deleting from database.'));
            }
            
            $this->setMessage($this->__('The user group was deleted.'));
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->redirect(href_admin('user_groups/list'));
    }
}