<?php
$migrationSql = array();

$migrationSql[] = "
CREATE TABLE `_locks` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COLLATE 'latin1_general_ci',
	PRIMARY KEY (`id`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB
;
";

$migrationSql[] = "
INSERT INTO _locks (name) VALUES
('migrations');
";