<?php

class controller_admin_surprises extends ControllerAdminModel {
    
    function _prehook()
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'surprises');
    }
    
    // List surprises
    function list_surprises()
    {
        $oSurpriseModel = new Surprise();
        
        // get all the categories
        $oSurprisesCollection = $oSurpriseModel->Get([], []);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Surprises'), MVC_ACTION_URL);
        
        $this->View->assign('oSurprisesCollection', $oSurprisesCollection);
        
        $this->View->addSEOParams($this->__('Surprises List :: Admin'), '', '');
    }
    
    // Add / Edit a surprise
    function edit()
    {
        $surpriseId = $this->filterGET('surprise_id', 'int');
        
        $oSurpriseModel = new Surprise();
        
        $FV = new FormValidation([
            'rules' => [
                'group_id'      => 'required',
                'name'          => 'required',
                'status'        => 'required',
                'description'   => ''
            ],
            'messages' => [
                'group_id'      => $this->__('Please choose a group'),
                'name'          => $this->__('Please specify a surprise name'),
                'status'        => $this->__('Please select a valid status')
            ]
        ]);
        
        $validateResult = $FV->validate();
        
        if ($this->isPOST()) {
            try {
                if (! $validateResult) {
                    throw new Exception($this->__('Please make sure you filled all mandatory values'));
                }
                if (!securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                // filter the values
                $groupId        = $this->filterPOST('group_id', 'int');
                $name           = $this->filterPOST('name', 'clean_html');
                $description    = $this->filterPOST('description', 'clean_html');
                $status         = $this->filterPOST('status', 'set[online,offline]');
                
                if (! $surpriseId) {
                    // check if another surprise with that name exists
                    $filters = [ 'name' => $name ];
                    $oCollection = $oSurpriseModel->Get($filters, []);
                    if (count($oCollection) > 0) {
                        throw new Exception($this->__('A surprise with that name already exists!'));
                    }
                }
                
                // set up the item to be saved
                $oItem = new SetterGetter();
                $oItem->setGroupId($groupId);
                $oItem->setName($name);
                $oItem->setDescription($description);
                $oItem->setStatus($status);
                
                // save to db
                if (! $surpriseId) {
                    $r = $oSurpriseModel->Add($oItem);
                }
                else {
                    $r = $oSurpriseModel->Edit($surpriseId, $oItem);
                }
                
                // check results
                if (!$r) {
                    throw new Exception($this->__('Error while saving to the database'));
                }
                
                $this->setMessage($this->__('The surprise was saved.'));
                $this->redirect(href_admin('surprises/list'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        // load the current surprise if on edit
        $oSurprise = new SetterGetter();
        if ($surpriseId) {
            $filters = [ 'surprise_id' => $surpriseId ];
            $oSurprise = $oSurpriseModel->singleGet($filters, []);
            
            $FV->initDefault($oSurprise);
        }
        
        // load all groups
        $oGroupModel = new Group();
        $oGroupsCollection = $oGroupModel->Get([], []);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Surprises'), href_admin('surprises/list'));
        
        if ($surpriseId) {
            $this->View->addSEOParams($this->__('Edit Surprise :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Edit Surprise'), CURRENT_URL);
        }
        else {
            $this->View->addSEOParams($this->__('Add Surprise :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Add Surprise'), CURRENT_URL);
        }
        
        $this->View->assign('FV', $FV);
        $this->View->assign('surpriseId', $surpriseId);
        $this->View->assign('oGroupsCollection', $oGroupsCollection);
    }
    
    // Delete surprises
    function delete()
    {
        $surpriseId = $this->filterGET('surprise_id', 'int');
        
        try {
            if (!securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (!$surpriseId) {
                throw new Exception($this->__('Group ID is missing.'));
            }
            
            // delete
            $oSurpriseModel = new Surprise();
            $r = $oSurpriseModel->Delete($surpriseId);
            if (!$r) {
                throw new Exception($this->__('Error while deleting from database.'));
            }
            
            $this->setMessage($this->__('The surprise was deleted.'));
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->redirect(href_admin('surprises/list'));
    }
}