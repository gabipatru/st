<?php

/*
 * CRUD for series table
 */
class Series extends DbData
{
    const TABLE_NAME    = 'series';
    const ID_FIELD      = 'series_id';
    
    protected $aFields = array(
        'series_id',
        'category_id',
        'name',
        'description',
        'status',
        'created_at'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
    
    protected function onGet($oCollection)
    {
        // get all category ids
        $ids = $oCollection->collectionColumn('categoryid');
        
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