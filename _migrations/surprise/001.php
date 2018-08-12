<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE `surprise` (
    `surprise_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `group_id` INT(10) UNSIGNED,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `status` ENUM('online', 'offline') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`surprise_id`),
    FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE INDEX (`name`),
    INDEX (`status`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";