<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE acl_permission (
    `acl_task_id` INT(10) UNSIGNED NOT NULL,
    `user_group_id` INT(10) UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`acl_task_id`) REFERENCES `acl_task`(`acl_task_id`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`user_group_id`) REFERENCES `user_group`(`user_group_id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE INDEX (`acl_task_id`, `user_group_id`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

$oAclTaskModel = new AclTask();

$query = "INSERT INTO `acl_permission` (acl_task_id, user_group_id) VALUES";
$parts = [];
foreach ($oAclTaskModel->getAllAclTasks() as $name => $taskId) {
    $parts[] = "($taskId, 1)";
}

$query = $query . implode(',', $parts);
$migrationSql[] = $query;

$migrationSql[] = "
INSERT INTO `acl_permission` (acl_task_id, user_group_id) VALUES
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(6, 2),
(7, 2),
(9, 2),
(10, 2)
";