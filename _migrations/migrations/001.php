<?php
$migrationSql = array();
$migrationSql[] = "
CREATE TABLE `_migration` (
	`migration_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COLLATE 'latin1_general_ci',
	`version` VARCHAR(255) NOT NULL COLLATE 'latin1_general_ci',
	PRIMARY KEY (`migration_id`),
    UNIQUE INDEX `_migrations_name` (`name`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

$migrationSql[] = "
CREATE TABLE `migration_log` (
	`migraion_log_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`migration_id` INT UNSIGNED NOT NULL,
	`query` TEXT NOT NULL,
    `duration` DECIMAL(10,3) NULL DEFAULT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`migraion_log_id`),
	CONSTRAINT `FK___migration` FOREIGN KEY (`migration_id`) REFERENCES `_migration` (`migration_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB
;
";
