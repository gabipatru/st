<?php

/*
 * CRUD for series table
 */
class Series extends DbData
{
    const TABLE_NAME    = 'series';
    const ID_FIELD      = 'series_id';
    
    const UPLOAD_DIR    = IMAGES_DIR .'/series';
    const HTTP_DIR      = HTTP_FILE_IMAGES .'/series';
    
    const IMAGE_WIDTH   = 250;
    const IMAGE_HEIGHT  = 250;

    protected $elasticSearchIndex = 'series';
    protected $elasticSearchType = 'series';

    protected $aFields = [
        'series_id',
        'category_id',
        'name',
        'description',
        'file',
        'status',
        'created_at'
    ];
    protected $aElasticFields = [
        'name',
        'description',
        'status'
    ];
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
    
    protected function onGet(Collection $oCollection): bool
    {
        // get all category ids
        $ids = $oCollection->databaseColumn('category_id');
        
        $oCategoryModel = new Category();
        $filters = [ 'category_id' => $ids ];
        $oCategoriesCollection = $oCategoryModel->Get($filters, []);
        
        // bind categories to their series
        foreach ($oCollection as $oCol) {
            $oCategory = $oCategoriesCollection->getById($oCol->getCategoryId());
            $oCol->setCategory($oCategory);
        }
        
        return true;
    }
}
