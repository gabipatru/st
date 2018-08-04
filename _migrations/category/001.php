<?php
$migrationSql = [];
$migrationSql[] = "
CREATE TABLE category (
    `category_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `status` ENUM('online', 'offline') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`category_id`),
    INDEX (`status`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";