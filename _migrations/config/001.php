<?php
$migrationSql = array();

$migrationSql[] ="
    CREATE TABLE config(
      config_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
      path VARCHAR(255) NOT NULL,
      value TEXT,
      type ENUM('text', 'textarea', 'yesno'),
      PRIMARY KEY (config_id),
      UNIQUE INDEX (`path`)
    )
    COLLATE='latin1_general_ci'
    ENGINE=InnoDB;
";