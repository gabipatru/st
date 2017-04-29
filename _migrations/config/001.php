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

// add config for Content Security Policy
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Security/Content Security Policy', 'default-src \'self\' \'unsafe-inline\' \'nonce-29af2i\' data:', 'textarea');
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

// add config for welcome email to a new user
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Users/Welcome Email', '1', 'yesno');
";

// add config for global email_form and email_from_name
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Email/Email Sending/Email From', 'website@mvc.ro', 'text');
";
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Email/Email Sending/Email From Name', 'Website', 'text');
";