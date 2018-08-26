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
            SELECT user.user_id
            FROM acl_permission 
            INNER JOIN user_group ON (user_group.user_group_id = acl_permission.user_group_id)
            INNER JOIN user ON (user.user_group_id = user_group.user_group_id)
            WHERE acl_permission.acl_task_id = ?
            AND user.user_id = ?
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