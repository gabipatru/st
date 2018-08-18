<?php

class controller_admin_series extends ControllerAdminModel
{
    function _prehook()
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'series');
    }
    
    // List series
    function list_series()
    {
        $oSeriesModel = new Series();
        
        // get all the categories
        $oSeriesCollection = $oSeriesModel->Get([], []);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Series'), MVC_ACTION_URL);
        
        $this->View->assign('oSeriesCollection', $oSeriesCollection);
        
        $this->View->addSEOParams($this->__('Series List :: Admin'), '', '');
    }
    
    // Edit a series
    function edit()
    {
        $seriesId = $this->filterGET('series_id', 'int');
        
        $oSeriesModel = new Series();
        
        $FV = new FormValidation([
            'rules' => [
                'category_id'   => 'required',
                'name'          => 'required',
                'status'        => 'required',
                'description'   => ''
            ],
            'messages' => [
                'category_id'   => $this->__('Please choose a category'),
                'name'          => $this->__('Please specify a series name'),
                'status'        => $this->__('Please select a valid status')
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
                $categoryId     = $this->filterPOST('category_id', 'int');
                $name           = $this->filterPOST('name', 'clean_html');
                $description    = $this->filterPOST('description', 'clean_html');
                $status         = $this->filterPOST('status', 'set[online,offline]');
                
                if (! $seriesId) {
                    // check if another category with that name exists
                    $filters = [ 'name' => $name ];
                    $oCollection = $oSeriesModel->Get($filters, []);
                    if (count($oCollection) > 0) {
                        throw new Exception($this->__('A series with that name already exists!'));
                    }
                }
                
                // set up the item to be saved
                $oItem = new SetterGetter();
                $oItem->setCategoryId($categoryId);
                $oItem->setName($name);
                $oItem->setDescription($description);
                $oItem->setStatus($status);
                
                // save to db
                if (! $seriesId) {
                    $r = $oSeriesModel->Add($oItem);
                }
                else {
                    $r = $oSeriesModel->Edit($seriesId, $oItem);
                }
                
                // check results
                if (!$r) {
                    throw new Exception($this->__('Error while saving to the database'));
                }
                
                $this->setMessage($this->__('The series was saved.'));
                $this->redirect(href_admin('series/list'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        // load the current series if on edit
        $oSeries = new SetterGetter();
        if ($seriesId) {
            $filters = [ 'series_id' => $seriesId ];
            $oSeries = $oSeriesModel->singleGet($filters, []);
            
            $FV->initDefault($oSeries);
        }
        
        // load all categories
        $oCategoryModel = new Category();
        $oCategoriesCollection = $oCategoryModel->Get([], []);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Series'), href_admin('series/list'));
        
        if ($seriesId) {
            $this->View->addSEOParams($this->__('Edit Series :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Edit Series'), CURRENT_URL);
        }
        else {
            $this->View->addSEOParams($this->__('Add Sereis :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Add Series'), CURRENT_URL);
        }
        
        $this->View->assign('FV', $FV);
        $this->View->assign('seriesId', $seriesId);
        $this->View->assign('oCategoriesCollection', $oCategoriesCollection);
    }
    
    // Delete series
    function delete()
    {
        $seriesId = $this->filterGET('series_id', 'int');
        
        try {
            if (!$this->securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (! Config::configByPath(DbData::ALLOW_DELETE_KEY)) {
                throw new Exception($this->__('Delete not allowed'));
            }
            if (!$seriesId) {
                throw new Exception($this->__('Series ID is missing.'));
            }
            
            // delete
            $oSeriesModel = new Series();
            $r = $oSeriesModel->Delete($seriesId);
            if (!$r) {
                throw new Exception($this->__('Error while deleting from database.'));
            }
            
            $this->setMessage($this->__('The series was deleted.'));
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->redirect(href_admin('series/list'));
    }
}