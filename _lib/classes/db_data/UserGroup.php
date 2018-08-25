<?php

/*
 * CRUD for user group table
 */
class UserGroup extends DbData
{
    const TABLE_NAME    = 'user_group';
    const ID_FIELD      = 'user_group_id';
    
    protected $aFields = array(
        'user_group_id',
        'name',
        'description',
        'status',
        'created_at'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
}