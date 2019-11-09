<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE acl_permission (
    acl_task_id integer NOT NULL,
    user_group_id integer NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK___acl_permission_task FOREIGN KEY (acl_task_id) 
        REFERENCES acl_task(acl_task_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK___acl_permisssion_group FOREIGN KEY (user_group_id) 
        REFERENCES user_group(user_group_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";

$migrationSql[] = "
CREATE UNIQUE INDEX idx_acl_permission ON acl_permission(acl_task_id, user_group_id);
";

$oAclTaskModel = new AclTask();

$query = "INSERT INTO acl_permission (acl_task_id, user_group_id) VALUES ";
$parts = [];
foreach ($oAclTaskModel->getAllAclTasks() as $name => $taskId) {
    $parts[] = "($taskId, 1)";
}

$query = $query . implode(',', $parts);
$migrationSql[] = $query;

$migrationSql[] = "
INSERT INTO acl_permission (acl_task_id, user_group_id) VALUES
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(10, 2)
";
