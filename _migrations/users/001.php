<?php
$migrationSql = array();
$migrationSql[] = "
CREATE TABLE `user`(
    `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` CHAR(60) NOT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `status` ENUM('active', 'new', 'banned') NOT NULL,
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE INDEX (`email`),
    UNIQUE INDEX (`username`),
    INDEX (`password`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";