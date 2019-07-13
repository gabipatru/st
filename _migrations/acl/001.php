<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE acl_task (
    acl_task_id serial PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
";

$oAclTaskModel = new AclTask();

$query = "INSERT INTO acl_task (acl_task_id, name) VALUES";
$parts = [];
foreach ($oAclTaskModel->getAllAclTasks() as $name => $taskId) {
    $parts[] = "($taskId, '$name')";
}

$query = $query . implode(',', $parts);
$migrationSql[] = $query;