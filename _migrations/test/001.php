<?php
$migrationSql = [];
$migrationSql[] = "
CREATE TABLE `test` (
	`test_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) COLLATE 'latin1_general_ci',
	PRIMARY KEY (`test_id`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";