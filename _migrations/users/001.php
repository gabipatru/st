<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE user_group (
    `user_group_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `status` ENUM('online', 'offline') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_group_id`),
    UNIQUE INDEX (`name`),
    INDEX (`status`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

$migrationSql[] = "
INSERT INTO user_group (name, description, status)
VALUES ('Super Admin', 'Super Admin group has rights to do everything', 'online')
";

$migrationSql[] = "
INSERT INTO user_group (name, description, status)
VALUES ('Normal Admin', 'Normal Admin can manage users and surprises', 'online')
";