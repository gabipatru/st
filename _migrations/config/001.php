<?php
$migrationSql = array();

$migrationSql[] ="
    CREATE TABLE `config`(
      `config_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      `path` VARCHAR(255) NOT NULL,
      `value` TEXT,
      `type` ENUM('text', 'textarea', 'yesno'),
      PRIMARY KEY (`config_id`),
      UNIQUE INDEX (`path`)
    )
    COLLATE='latin1_general_ci'
    ENGINE=InnoDB;
";

// add config for user activation control
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Users/Enable User Confirmation', '1', 'yesno');
";

// add config for confirmation code expiry
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Users/Confirmation expiry', '1 day', 'text');
";