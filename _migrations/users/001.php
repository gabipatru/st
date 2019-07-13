<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TYPE user_group_status AS ENUM ('online', 'offline');
";

$migrationSql[] = "
CREATE TABLE user_group (
    user_group_id serial PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    status user_group_status NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
";

$migrationSql[] = "
CREATE INDEX idx_user_group_status ON user_group(status);
";

$migrationSql[] = "
INSERT INTO user_group (name, description, status)
VALUES ('Super Admin', 'Super Admin group has rights to do everything', 'online')
";

$migrationSql[] = "
INSERT INTO user_group (name, description, status)
VALUES ('Normal Admin', 'Normal Admin can manage users and surprises', 'online')
";