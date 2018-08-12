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

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('3', 'Turbo Sport 211 - 280', 'A patra grupa Turbo Sport', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('3', 'Turbo Sport 401 - 470', 'A cincea grupa Turbo Sport', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('4', 'Turbo Super 331 - 400', 'Turbo Super', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('4', 'Turbo Super 401 - 470', 'Turbo Super', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('4', 'Turbo Super 471 - 540', 'Turbo Super', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('5', 'Turbo 2000 71 - 140', 'Turbo 2000', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('6', 'Turbo 2014 1 - 160', 'Turbo 2014', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('7', 'Turbo Sport 2003 1 - 99', 'Turbo Sport 2003', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('8', 'Turbo Super 2003 1 - 99', 'Turbo Super 2003', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('9', 'Lazer 1 - 70', 'Lazer 1 - 70', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('10', 'Lazer Blue 1 - 100', 'Lazer Blue 1 - 100', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('11', 'Ülker Final \'86 1 - 60', 'Ülker Final \'86 1 - 60', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('12', 'Ülker Final \'88 1 - 50', 'Ülker Final \'88 1 - 50', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('13', 'Ülker Final \'90 1 - 70', 'Ülker Final \'90 1 - 70', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('14', 'Ülker Final \'92 1 - 70', 'Ülker Final \'92 1 - 70', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('15', 'Otto Moto 1 - 100', 'Seria Otto Moto 1 - 100', 'online')
";

$migrationSql[] = "
INSERT INTO `group` (series_id, name, description, status)
VALUES ('15', 'Otto Moto 101 - 200', 'Seria Otto Moto 101 - 200', 'online')
";