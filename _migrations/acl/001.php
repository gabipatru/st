<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE acl_task (
    `acl_task_id` INT(10) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`acl_task_id`),
    UNIQUE INDEX (`name`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

$oAclTaskModel = new AclTask();

$query = "INSERT INTO `acl_task` (acl_task_id, name) VALUES";
$parts = [];
foreach ($oAclTaskModel->getAllAclTasks() as $name => $taskId) {
    $parts[] = "($taskId, '$name')";
}

$query = $query . implode(',', $parts);
$migrationSql[] = $query;