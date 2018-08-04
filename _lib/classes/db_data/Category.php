<?php

/*
 * CRUD for category table
 */
class Category extends DbData
{
    const TABLE_NAME    = 'category';
    const ID_FIELD      = 'category_id';
    
    protected $aFields = array(
        'category_id',
        'name',
        'description',
        'status',
        'created_at'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
}