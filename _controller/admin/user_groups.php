<?php

class controller_admin_user_groups extends ControllerAdminModel
{
    function _prehook()
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'users');
    }

    ###############################################################################
    ## LIST USER GROUPS PAGE
    ###############################################################################
    function list_user_groups()
    {
        $oUserGroupModel = new UserGroup();
        
        // get all the categories
        $oGroupsCollection = $oUserGroupModel->Get();
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Users'), href_admin('users/list_users'));
        $Breadcrumbs->Add($this->__('User Groups'), MVC_ACTION_URL);
        
        // ACL tasks
        $oAclTaskModel = new AclTask();
        $aAclTasks = $oAclTaskModel->getAllAclTasks();
        
        // ACL tasks for groups
        $groupIds = [];
        foreach ($oGroupsCollection as $oGroup) {
            $groupIds[] = $oGroup->getUserGroupId();
        }
        $oAclPermissionModel = new AclPermission();
        $filters = [ 'user_group_id' => $groupIds ];
        $oPermissionCollection = $oAclPermissionModel->Get($filters);
        
        // create an usable permissions array
        $aPermission = [];
        foreach ($oPermissionCollection as $oItem) {
            $aPermission[$oItem->getUserGroupId()][] = $oItem->getAclTaskId();
        }
        
        $this->View->assign('oGroupsCollection', $oGroupsCollection);
        $this->View->assign_by_ref('aAclTasks', $aAclTasks);
        $this->View->assign_by_ref('aPermission', $aPermission);
        
        $this->View->addSEOParams($this->__('User Groups List :: Admin'), '', '');
    }

    ###############################################################################
    ## ADD / EDIT USER GROUPS PAGE
    ###############################################################################
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
        
        $validateResult = $this->validate($FV);
        
        if ($this->isPOST()) {
            try {
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                if (! $validateResult) {
                    throw new Exception($this->__('Please make sure you filled all mandatory values'));
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
        $Breadcrumbs->Add($this->__('User Groups'), href_admin('user_groups/list'));
        
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

    ###############################################################################
    ## DELETE USER GROUPS PAGE
    ###############################################################################
    function delete()
    {
        $userGroupId = $this->filterGET('user_group_id', 'int');
        
        try {
            if (! $this->securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (! $this->deleteIsAllowed()) {
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

    ###############################################################################
    ## CHANGE PERMISSIONS ENDPOINT
    ###############################################################################
    function ajax_change_permission() 
    {
        $groupId = $this->filterPOST('user_group_id', 'int');
        $aPermission = $this->filterPOST('permissions');
        $sToken = $this->filterPOST('token', 'string');
        
        $oAclPermissionModel = new AclPermission();
        
        try {
            if (! $this->securityCheckToken($sToken)) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (! $groupId) {
                throw new Exception($this->__('The group id is missing'));
            }
            if (! is_array($aPermission)) {
                throw new Exception($this->__('New permissions are incorrect'));
            }
            
            $this->db->startTransaction();
        
            // delete existing permissions for the group
            $filters = [ 'user_group_id' => $groupId ];
            $r = $oAclPermissionModel->DeleteByColumn($filters);
            if (!$r) {
                throw new Exception($this->__('Could not delete old permissions from the database'));
            }
            
            // add new permissions
            foreach ($aPermission as $taskId) {
                $oItem = new SetterGetter();
                $oItem->setAclTaskId($taskId);
                $oItem->setUserGroupId($groupId);
                $r = $oAclPermissionModel->Add($oItem);
                if (!$r) {
                    throw new Exception($this->__('Could not add new permissions to the database'));
                }
            }
            
            $this->db->commitTransaction();
            $this->JsonSuccess();
        }
        catch (Exception $e) {
            $this->db->rollbackTransaction();
            $this->JsonError($e->getMessage());
        }
        
        exit;
    }
}