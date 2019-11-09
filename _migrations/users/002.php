<?php
$migrationSql = array();

$migrationSql[] = "
CREATE TYPE user_status AS ENUM ('active', 'new', 'banned');
";

$migrationSql[] = "
CREATE TABLE users (
    user_id serial PRIMARY KEY,
    user_group_id integer,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    password CHAR(40) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    status user_status NOT NULL,
    is_admin integer NOT NULL DEFAULT 0,
    last_login TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK___users FOREIGN KEY (user_group_id) 
        REFERENCES user_group(user_group_id) ON UPDATE CASCADE
);
";

$migrationSql[] = "
CREATE INDEX idx_user_password ON users(password);
";

// add admin user
$migrationSql[] = "
INSERT INTO users (user_group_id, email, username, password, first_name, last_name, status, is_admin) VALUES
(1, 'gabipatru@gmail.com', 'admin', '632ccb20c31207fb22bf34a5c32fc4e24f6779aa', 'Gabi', 'Patru', 'active', 1),
(2, 'editor@st.ro', 'editor', '632ccb20c31207fb22bf34a5c32fc4e24f6779aa', 'Editor', 'ST', 'active', 1);
";
