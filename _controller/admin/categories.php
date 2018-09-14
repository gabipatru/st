<?php

class controller_admin_categories extends ControllerAdminModel
{
    function _prehook() 
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'categories');
    }
    
    // List categories
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
    
    // Add / edit a category
    function edit()
    {
        $categoryId = $this->filterGET('category_id', 'int');
        
        $oCategoryModel = new Category();
        
        $FV = new FormValidation([
            'rules' => [
                'name'          => 'required',
                'status'        => 'required',
                'description'   => '',
                'fileImage'     => ''
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
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
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
                $oItem->setFile(null);
                $oItem->setStatus($status);
                
                $this->db->startTransaction();
                
                // save to db
                if (! $categoryId) {
                    $categoryId = $oCategoryModel->Add($oItem);
                    $r = ( $categoryId ? true : false );
                }
                else {
                    $r = $oCategoryModel->Edit($categoryId, $oItem);
                }
                
                // check results
                if (!$r) {
                    throw new Exception($this->__('Error while saving to the database'));
                }
                
                // upload the file
                $uploader = new ImageUpload();
                $uploader->setFieldName('fileImage');
                if ( $uploader->fileExists() ) {
                    $uploader->setUploadPath( Category::UPLOAD_DIR );
                    $uploader->setFileName( $categoryId );
                    $uploader->ResizeTo( Category::IMAGE_WIDTH, Category::IMAGE_HEIGHT );
                    $r = $uploader->Upload();
                    if (!$r) {
                        throw new Exception($this->__('Could not upload image'));
                    }
                    
                    // make another update in order to save the file name
                    $file = $uploader->getDestinationFile();
                    $oItem->setFile($file);
                    $r = $oCategoryModel->Edit($categoryId, $oItem);
                    if (!$r) {
                        throw new Exception($this->__('Error while saving to the database'));
                    }
                }
                
                $this->db->commitTransaction();
                
                $this->setMessage($this->__('The category was saved.'));
                $this->redirect(href_admin('categories/list'));
            }
            catch (Exception $e) {
                $this->db->rollbackTransaction();
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $oCategory = new SetterGetter();
        if ($categoryId) {
            $filters = [ 'category_id' => $categoryId ];
            $oCategory = $oCategoryModel->singleGet($filters, []);
                
            $FV->initDefault($oCategory);
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
        $this->View->assign('oCategory', $oCategory);
        $this->View->assign('categoryId', $categoryId);
    }
    
    // Delete a category
    function delete()
    {
        $categoryId = $this->filterGET('category_id', 'int');
        
        try {
            if (! $this->securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (Config::configByPath(DbData::ALLOW_DELETE_KEY) === Config::CONFIG_VALUE_NO) {
                throw new Exception($this->__('Delete not allowed'));
            }
            if (! $categoryId) {
                throw new Exception($this->__('Category ID is missing.'));
            }
            
            $this->db->startTransaction();
            
            // delete
            $oCategoryModel = new Category();
            
            // load the category to be deleted
            $filters = [ 'category_id' => $categoryId ];
            $oCategory = $oCategoryModel->singleGet($filters);
            
            $r = $oCategoryModel->Delete($categoryId);
            if (!$r) {
                throw new Exception($this->__('Error while deleting from database.'));
            }
            
            if ($oCategory->getFile()) {
                if ( ! unlink( Category::UPLOAD_DIR .'/'. $oCategory->getFile() ) ) {
                    throw new Exception($this->__('Could not delete category file.'));
                }
            }
            
            $this->db->commitTransaction();
            
            $this->setMessage($this->__('The category was deleted.'));
        }
        catch (Exception $e) {
            $this->db->rollbackTransaction();
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->redirect(href_admin('categories/list'));
    }
}