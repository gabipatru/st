<?php

/*
 * CRUD for group table
 */
class Group extends DbData
{
    const TABLE_NAME    = 'group';
    const ID_FIELD      = 'group_id';
    
    protected $aFields = array(
        'group_id',
        'series_id',
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
        // get all series ids
        $ids = $oCollection->collectionColumn('seriesid');
        
        // load the required series
        $oSeriesModel = new Series();
        $filters = [ 'series_id' => $ids ];
        $oSeriesCollection = $oSeriesModel->Get($filters, []);
        
        // bind series to their groups
        foreach ($oCollection as $oCol) {
            $oSeries = $oSeriesCollection->getById($oCol->getSeriesId());
            $oCol->setSeries($oSeries);
        }
        
        return true;
    }
}