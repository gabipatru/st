<?php

/*
 * CRUD for Surprize table
 */
class Surprise extends DbData
{
    const TABLE_NAME    = 'surprise';
    const ID_FIELD      = 'surprise_id';
    
    protected $aFields = array(
        'surprise_id',
        'group_id',
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
        $ids = $oCollection->collectionColumn('groupid');
        
        // load the required series
        $oGroupModel = new Group();
        $filters = [ 'group_id' => $ids ];
        $oGroupsCollection = $oGroupModel->Get($filters, []);
        
        // bind series to their groups
        foreach ($oCollection as $oCol) {
            $oGroup = $oGroupsCollection->getById($oCol->getGroupId());
            $oCol->setGroup($oGroup);
        }
        
        return true;
    }
}