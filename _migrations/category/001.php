<?php
$migrationSql = [];

$migrationSql[] = "DROP TYPE IF EXISTS category_status";

$migrationSql[] = "
CREATE TYPE category_status AS ENUM ('online', 'offline');
";

$migrationSql[] = "
CREATE TABLE category (
    category_id serial PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    file VARCHAR(255),
    status category_status NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
";

$migrationSql[] = "
CREATE INDEX idx_category_status ON category(status);
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
