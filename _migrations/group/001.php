<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE `group` (
    `group_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `series_id` INT(10) UNSIGNED,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `status` ENUM('online', 'offline') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`group_id`),
    FOREIGN KEY (`series_id`) REFERENCES `series` (`series_id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE INDEX (`name`),
    INDEX (`status`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('1', 'Turbo 1 - 50', 'Prima grupa Turbo', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('1', 'Turbo 51 - 120', 'A doua grupa Turbo', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('1', 'Turbo 121 - 190', 'A treia grupa Turbo', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('1', 'Turbo 261 - 330', 'A patra grupa Turbo', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('2', 'Turbo Classic 1 - 70', 'Prima grupa Turbo Classic', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('2', 'Turbo Classic 71 - 140', 'A doua grupa Turbo Classic', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('3', 'Turbo Sport 1 - 70', 'Prima grupa Turbo Sport', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('3', 'Turbo Sport 71 - 140', 'A doua grupa Turbo Sport', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('3', 'Turbo Sport 141 - 210', 'A treia grupa Turbo Sport', 'online')
";