<?php

/*
 * CRUD for acl_permission table
 */
class AclPermission extends DbData
{
    const TABLE_NAME    = 'acl_permission';
    const ID_FIELD      = 'acl_task_id';
    
    protected $aFields = array(
        'acl_task_id',
        'user_group_id'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
}