<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TYPE surprise_status AS ENUM ('online', 'offline');
";

$migrationSql[] = "
CREATE TABLE surprise (
    surprise_id serial PRIMARY KEY,
    group_id integer,
    name VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    status surprise_status NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK___surprise FOREIGN KEY (group_id) 
        REFERENCES groups (group_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";

$migrationSql[] = "
CREATE INDEX idx_surprise_status ON surprise(status);
";

// add Turbo 1 - 50
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=50; $i++) {
    $parts[] = "(1, 'Turbo $i', 'Turbo $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo 51 - 120
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=51; $i<=120; $i++) {
    $parts[] = "(2, 'Turbo $i', 'Turbo $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo 121 - 190
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=121; $i<=190; $i++) {
    $parts[] = "(3, 'Turbo $i', 'Turbo $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo 191 - 260
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=191; $i<=260; $i++) {
    $parts[] = "(4, 'Turbo $i', 'Turbo $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo 261 - 330
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=261; $i<=330; $i++) {
    $parts[] = "(5, 'Turbo $i', 'Turbo $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Classic 1 - 70
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=70; $i++) {
    $parts[] = "(6, 'Turbo Classic $i', 'Turbo Classic $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Classic 71 - 140
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=71; $i<=140; $i++) {
    $parts[] = "(7, 'Turbo Classic $i', 'Turbo Classic $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Sport 1 - 70
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=70; $i++) {
    $parts[] = "(8, 'Turbo Sport $i', 'Turbo Sport $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Sport 71 - 140
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=71; $i<=140; $i++) {
    $parts[] = "(9, 'Turbo Sport $i', 'Turbo Sport $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Sport 141 - 210
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=141; $i<=210; $i++) {
    $parts[] = "(10, 'Turbo Sport $i', 'Turbo Sport $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Sport 211 - 280
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=211; $i<=280; $i++) {
    $parts[] = "(11, 'Turbo Sport $i', 'Turbo Sport $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Sport 401 - 470
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=401; $i<=470; $i++) {
    $parts[] = "(12, 'Turbo Sport $i', 'Turbo Sport $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Sport 471 - 540
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=471; $i<=540; $i++) {
    $parts[] = "(13, 'Turbo Sport $i', 'Turbo Sport $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Super 331 - 400
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=331; $i<=400; $i++) {
    $parts[] = "(14, 'Turbo Super $i', 'Turbo Super $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Super 401 - 470
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=401; $i<=470; $i++) {
    $parts[] = "(15, 'Turbo Super $i', 'Turbo Super $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Super 471 - 540
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=471; $i<=540; $i++) {
    $parts[] = "(16, 'Turbo Super $i', 'Turbo Super $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo 2000 71 - 140
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=71; $i<=140; $i++) {
    $parts[] = "(17, 'Turbo 2000 $i', 'Turbo 2000 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo 2014 1 - 160
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=160; $i++) {
    $parts[] = "(18, 'Turbo 2014 $i', 'Turbo 2014 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Sport 2003 1 - 99
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=99; $i++) {
    $parts[] = "(19, 'Turbo Sport 2003 $i', 'Turbo Sport 2003 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Turbo Super 2003 1 - 99
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=99; $i++) {
    $parts[] = "(20, 'Turbo Super 2003 $i', 'Turbo Super 2003 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Lazer 1 - 70
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=70; $i++) {
    $parts[] = "(21, 'Lazer $i', 'Lazer $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Lazer Blue 1 - 70
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=70; $i++) {
    $parts[] = "(22, 'Lazer Blue $i', 'Lazer Blue $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Ülker Final '86 1 - 60
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=60; $i++) {
    $parts[] = "(23, 'Ülker Final ''86 $i', 'Ülker Final ''86 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Ülker Final '88 1 - 50
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=50; $i++) {
    $parts[] = "(24, 'Ülker Final ''88 $i', 'Ülker Final ''88 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Ülker Final '90 1 - 70
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=70; $i++) {
    $parts[] = "(25, 'Ülker Final ''90 $i', 'Ülker Final ''90 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Ülker Final '92 1 - 70
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=70; $i++) {
    $parts[] = "(26, 'Ülker Final ''92 $i', 'Ülker Final ''92 $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Otto Moto 1 - 100
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=1; $i<=100; $i++) {
    $parts[] = "(27, 'Otto Moto $i', 'Otto Moto $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;

// add Otto Moto 101 - 200
$query = "INSERT INTO surprise (group_id, name, description, status) VALUES";
$parts = [];
for ($i=101; $i<=200; $i++) {
    $parts[] = "(28, 'Otto Moto $i', 'Otto Moto $i', 'online')";
}
$query = $query . implode(',', $parts);
$migrationSql[] = $query;