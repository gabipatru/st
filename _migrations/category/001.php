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
    UNIQUE INDEX (`name`),
    INDEX (`status`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

$migrationSql[] = "
INSERT INTO category (name, description, status)
VALUES ('Turbo', 'Seriile Turbo', 'online')
";

$migrationSql[] = "
INSERT INTO category (name, description, status)
VALUES ('Lazer', 'Seriile Lazer', 'online')
";

$migrationSql[] = "
INSERT INTO category (name, description, status)
VALUES ('Ülker', 'Seriile Ülker final', 'online')
";

$migrationSql[] = "
INSERT INTO category (name, description, status)
VALUES ('Otto Moto', 'Seriile Otto Moto', 'online')
";