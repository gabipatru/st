<?php

class controller_admin_categories extends ControllerAdminModel
{
    function _prehook() 
    {
        parent::_prehook();
        
        $this->View->assign('menu', 'categories');
    }

    ###############################################################################
    ## LIST CATEGORIES PAGE
    ###############################################################################
    function list_categories()
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

        $oCategories = new Category();
        
        // get the categories
        $filters = $GF->filters();
        $options = [
            'search' => $search,
            'search_fields' => [ 'name' ],
            'order_field' => $sort,
            'order_type' => $sort_crit
        ];
        $oCategoriesCollection = $oCategories->Get($filters, $options);
        
        $Breadcrumbs = Breadcrumbs::getSingleton();
        $Breadcrumbs->Add($this->__('Categories'), MVC_ACTION_URL);
        
        $this->View->assign('oCategoriesCollection', $oCategoriesCollection);
        $this->View->assign('search', $search);
        $this->View->assign('GF', $GF);
        $this->View->assign('sort', $sort);
        $this->View->assign('sort_crit', $sort_crit);
        
        $this->View->addSEOParams($this->__('Categories List :: Admin'), '', '');
    }

    ###############################################################################
    ## ADD / EDIT CATEGORIES PAGE
    ###############################################################################
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
                if ($this->db->transactionLevel()) {
                    $this->db->rollbackTransaction();
                }
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

    ###############################################################################
    ## DELETE CATEGORY PAGE
    ###############################################################################
    function delete()
    {
        $categoryId = $this->filterGET('category_id', 'int');
        
        try {
            if (! $this->securityCheckToken($this->filterGET('token', 'string'))) {
                throw new Exception($this->__('The page delay was too long'));
            }
            if (! $this->deleteIsAllowed()) {
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
            if ($this->db->transactionLevel() > 0) {
                $this->db->rollbackTransaction();
            }
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->redirect(href_admin('categories/list'));
    }
}
