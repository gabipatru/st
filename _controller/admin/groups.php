<?php

class controller_admin_groups extends ControllerAdminModel
{
    function _prehook()
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'groups');
    }

    ###############################################################################
    ## LIST GROUPS PAGE
    ###############################################################################
    function list_groups()
    {
        $search     = $this->filterGET('search', 'string');
        $sort       = $this->filterGET('sort', 'string');
        $sort_crit  = $this->filterGET('sort_crit', 'set[asc,desc]');

        $GF = new GridFilters([
            'status' => [
                'default' => false,
                'valid_values' => [ Category::SERIES_ONLINE, Category::SERIEES_OFFLINE ]
            ]
        ]);

        $oGroupModel = new Group();
        
        // get the groups
        $filters = $GF->filters();
        $options = [
            'search' => $search,
            'search_fields' => [ 'name' ],
            'order_field' => $sort,
            'order_type' => $sort_crit
        ];
        $oGroupsCollection = $oGroupModel->Get($filters, $options);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Groups'), MVC_ACTION_URL);
        
        $this->View->assign('oGroupsCollection', $oGroupsCollection);
        $this->View->assign('search', $search);
        $this->View->assign('GF', $GF);
        $this->View->assign('sort', $sort);
        $this->View->assign('sort_crit', $sort_crit);
        
        $this->View->addSEOParams($this->__('Groups List :: Admin'), '', '');
    }

    ###############################################################################
    ## ADD / EDIT SERIES PAGE
    ###############################################################################
    function edit()
    {
        $groupId = $this->filterGET('group_id', 'int');
        
        $oGroupModel = new Group();
        
        $FV = new FormValidation([
            'rules' => [
                'series_id'     => 'required',
                'name'          => 'required',
                'status'        => 'required',
                'description'   => ''
            ],
            'messages' => [
                'series_id'     => $this->__('Please choose a series'),
                'name'          => $this->__('Please specify a group name'),
                'status'        => $this->__('Please select a valid status')
            ]
        ]);

        $validate = $this->validate($FV);
        
        if ($this->isPOST()) {
            try {
                if (! $this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                if (! $validate) {
                    throw new Exception($this->__('Please make sure you filled all mandatory values'));
                }
                
                // filter the values
                $seriesId       = $this->filterPOST('series_id', 'int');
                $name           = $this->filterPOST('name', 'clean_html');
                $description    = $this->filterPOST('description', 'clean_html');
                $status         = $this->filterPOST('status', 'set[online,offline]');
                
                if (! $groupId) {
                    // check if another group with that name exists
                    $filters = [ 'name' => $name ];
                    $oCollection = $oGroupModel->Get($filters, []);
                    if (count($oCollection) > 0) {
                        throw new Exception($this->__('A group with that name already exists!'));
                    }
                }
                
                // set up the item to be saved
                $oItem = new SetterGetter();
                $oItem->setSeriesId($seriesId);
                $oItem->setName($name);
                $oItem->setDescription($description);
                $oItem->setStatus($status);
                
                // save to db
                if (! $groupId) {
                    $r = $oGroupModel->Add($oItem);
                }
                else {
                    $r = $oGroupModel->Edit($groupId, $oItem);
                }
                
                // check results
                if (!$r) {
                    throw new Exception($this->__('Error while saving to the database'));
                }
                
                $this->setMessage($this->__('The group was saved.'));
                $this->redirect(href_admin('groups/list'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        // load the current series if on edit
        $oGroup = new SetterGetter();
        if ($groupId) {
            $filters = [ 'group_id' => $groupId ];
            $oGroup = $oGroupModel->singleGet($filters, []);
            
            $FV->initDefault($oGroup);
        }
        
        // load all categories
        $oSeriesModel = new Series();
        $oSeriesCollection = $oSeriesModel->Get([], []);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Groups'), href_admin('groups/list'));
        
        if ($groupId) {
            $this->View->addSEOParams($this->__('Edit Group :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Edit Group'), CURRENT_URL);
        }
        else {
            $this->View->addSEOParams($this->__('Add Group :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Add Group'), CURRENT_URL);
        }
        
        $this->View->assign('FV', $FV);
        $this->View->assign('groupId', $groupId);
        $this->View->assign('oSeriesCollection', $oSeriesCollection);
    }

    ###############################################################################
    ## DELETE SERIES PAGE
    ###############################################################################
    function delete()
    {
        $groupId = $this->filterGET('group_id', 'int');
        
        try {
            if (! $this->securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (! $this->deleteIsAllowed()) {
                throw new Exception($this->__('Delete not allowed'));
            }
            if (! $groupId) {
                throw new Exception($this->__('Group ID is missing.'));
            }
            
            // delete
            $oGroupModel = new Group();
            $r = $oGroupModel->Delete($groupId);
            if (!$r) {
                throw new Exception($this->__('Error while deleting from database.'));
            }
            
            $this->setMessage($this->__('The group was deleted.'));
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->redirect(href_admin('groups/list'));
    }
}