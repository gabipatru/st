<?php

class controller_admin_categories extends ControllerAdminModel
{
    function _prehook() 
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'categories');
    }
    
    function list_categories()
    {
        $oCategories = new Category();
        
        // get all the categories
        $oCategoriesCollection = $oCategories->Get([], []);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Categories'), MVC_ACTION_URL);
        
        $this->View->assign('oCategoriesCollection', $oCategoriesCollection);
        
        $this->View->addSEOParams($this->__('Categories List :: Admin'), '', '');
    }
    
    function edit()
    {
        $categoryId = $this->filterGET('category_id', 'int');
        
        $oCategoryModel = new Category();
        
        $FV = new FormValidation([
            'rules' => [
                'name'          => 'required',
                'status'        => 'required',
                'description'   => ''
            ],
            'messages' => [
                'name'      => $this->__('Please specify a category name'),
                'status'    => $this->__('Please select a valid status')
            ]
        ]);
        
        $validateResult = $FV->validate();
        
        if ($this->isPOST()) {
            try {
                if (! $validateResult) {
                    throw new Exception($this->__('Please make sure you filled all mandatory values'));
                }
                if (!securityCheckToken(filter_post('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                // filter the values
                $name = $this->filterPOST('name', 'clean_html');
                $description = $this->filterPOST('description', 'clean_html');
                $status = $this->filterPOST('status', 'set[online,offline]');
            
                if (! $categoryId) {
                    // check if another category with that name exists
                    $filters = [ 'name' => $name ];
                    $oCollection = $oCategoryModel->Get($filters, []);
                    if (count($oCollection) > 0) {
                        throw new Exception($this->__('A category with that name already exists!'));
                    }
                }
                
                // set up the item to be saved
                $oItem = new SetterGetter();
                $oItem->setName($name);
                $oItem->setDescription($description);
                $oItem->setStatus($status);
                
                // save to db
                if (! $categoryId) {
                    $r = $oCategoryModel->Add($oItem);
                }
                else {
                    $r = $oCategoryModel->Edit($categoryId, $oItem);
                }
                
                // check results
                if (!$r) {
                    throw new Exception($this->__('Error while saving to the database'));
                }
                
                $this->setMessage($this->__('The category was saved.'));
                $this->redirect(href_admin('categories/list'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        else {
            $oCategory = new SetterGetter();
            if ($categoryId) {
                $filters = [ 'category_id' => $categoryId ];
                $oCategory = $oCategoryModel->singleGet($filters, []);
                
                $FV->initDefault($oCategory);
            }
        }
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Categories'), MVC_ACTION_URL);
        
        if ($categoryId) {
            $this->View->addSEOParams($this->__('Edit Category :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Edit Category'), CURRENT_URL);
        }
        else {
            $this->View->addSEOParams($this->__('Add Category :: Admin'), '', '');
            $Breadcrumbs->Add($this->__('Add Category'), CURRENT_URL);
        }
        
        $this->View->assign('FV', $FV);
    }
}