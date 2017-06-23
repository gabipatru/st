<?php
$migrationSql = array();

$migrationSql[] = "
CREATE TABLE `email_queue` (
  `email_queue_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `to` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255),
  `body` TEXT,
  `priority` SMALLINT UNSIGNED NOT NULL DEFAULT 10,
  `status` ENUM('sent', 'not sent') NOT NULL DEFAULT 'not sent',
  `send_attempts` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email_queue_id`),
  KEY (`status`, `send_attempts`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

$migrationSql[] = "
CREATE TABLE `email_log` (
  `email_log_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_queue_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `status` ENUM('sent', 'not sent') NOT NULL,
  `error_info` TEXT,
  `debug` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email_log_id`),
  FOREIGN KEY (`email_queue_id`) REFERENCES `email_queue`(`email_queue_id`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";