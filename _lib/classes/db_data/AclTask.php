<?php

/*
 * CRUD for acl_tasks table
 */
class AclTask extends DbData
{
    const TABLE_NAME    = 'acl_task';
    const ID_FIELD      = 'acl_task_id';
    
    private $aclTasks = [
        'admin/dashboard'       => 1,
        'admin/cache'           => 2,
        'admin/categories'      => 3,
        'admin/config'          => 4,
        'admin/email'           => 5,
        'admin/groups'          => 6,
        'admin/series'          => 7,
        'admin/surprises'       => 8,
        'admin/user_groups'     => 9,
        'admin/users'           => 10
    ];
    
    protected $aFields = array(
        'acl_task_id',
        'name'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
    
    public function getAllAclTasks(): array
    {
        return $this->aclTasks;
    }
    
    public function getAclTaskId(string $name): int
    {
        if (isset($this->aclTasks[$name])) {
            return $this->aclTasks[$name];
        }
        
        return 0;
    }
}