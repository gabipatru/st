<?php
$migrationSql = array();

$migrationSql[] = "
CREATE TABLE `email_log` (
  `email_log_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `to` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255),
  `body` TEXT,
  `status` ENUM('sent', 'not sent') NOT NULL,
  `error_info` TEXT,
  `debug` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email_log_id`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";