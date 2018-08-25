<?php
$migrationSql = array();
$migrationSql[] = "
CREATE TABLE `user`(
    `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_group_id` INT(10) UNSIGNED,
    `email` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` CHAR(40) NOT NULL,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `status` ENUM('active', 'new', 'banned') NOT NULL,
    `is_admin` SMALLINT NOT NULL DEFAULT 0,
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    FOREIGN KEY (`user_group_id`) REFERENCES `user_group`(`user_group_id`) ON UPDATE CASCADE,
    UNIQUE INDEX (`email`),
    UNIQUE INDEX (`username`),
    INDEX (`password`)
)
COLLATE='latin1_general_ci'
ENGINE=InnoDB;
";

// add admin user
$migrationSql[] = "
INSERT INTO `user` (user_group_id, email, username, password, first_name, last_name, status, is_admin) VALUES
(1, 'gabipatru@gmail.com', 'admin', '632ccb20c31207fb22bf34a5c32fc4e24f6779aa', 'Gabi', 'Patru', 'active', 1),
(2, 'editor@st.ro', 'editor', '632ccb20c31207fb22bf34a5c32fc4e24f6779aa', 'Editor', 'ST', 'active', 1);
";