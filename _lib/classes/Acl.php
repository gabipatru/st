<?php

/*
 * Perform checks to find out if a user has the permission to perform an action
 */
class Acl
{
    use Singleton;
    
    private $oAclTasks      = null;
    private $oAclPermission = null;
    private $db             = null;
    
    private function onGetSingleton()
    {
        if (! $this->oAclTasks) {
            $this->oAclTasks = new AclTask();
        }
        if (! $this->oAclPermission) {
            $this->oAclPermission = new AclPermission();
        }
        if (! $this->db) {
            $this->db = db::getSingleton();
        }
    }
    
    public function getTaskId(string $name) :int
    {
        return $this->oAclTasks->getAclTaskId($name);
    }
    
    public function checkPermission(int $taskId, int $userId) :bool
    {
        // check if ACL is enabled
        if (! Config::configByPath('/Website/ACL/Enable ACL')) {
            return true;
        }
        
        // the complex query
        $sql = "
            SELECT ".User::TABLE_NAME.".user_id
            FROM ".AclPermission::TABLE_NAME." 
            INNER JOIN ".UserGroup::TABLE_NAME." 
                ON (".UserGroup::TABLE_NAME.".user_group_id = ".AclPermission::TABLE_NAME.".user_group_id)
            INNER JOIN ".User::TABLE_NAME." 
                ON (".User::TABLE_NAME.".user_group_id = ".UserGroup::TABLE_NAME.".user_group_id)
            WHERE ".AclPermission::TABLE_NAME.".acl_task_id = $1
            AND ".User::TABLE_NAME.".user_id = $2
        ";
        
        $aParams = [$taskId, $userId];
        
        // ruun query
        $res = $this->db->query($sql, $aParams);
        
        if ($this->db->rowCount($res) > 0) {
            return true;
        }
        
        return false;
    }
}