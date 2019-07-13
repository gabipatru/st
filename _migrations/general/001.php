<?php
$migrationSql = array();

$migrationSql[] = "
CREATE TABLE _locks (
    id serial PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);
";

$migrationSql[] = "
INSERT INTO _locks (name) VALUES
('migrations');
";