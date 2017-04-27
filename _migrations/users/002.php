<?php
$migrationSql = array();
$migrationSql[] = "
CREATE TABLE `user_confirmation`(
  `confirmation_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `code` CHAR(32),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`confirmation_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX (`code`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";