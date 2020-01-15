<?php
$migrationSql = [];

$migrationSql[] = "DROP TYPE IF EXISTS series_status";

$migrationSql[] = "
CREATE TYPE series_status AS ENUM ('online', 'offline');
";

$migrationSql[] = "
CREATE TABLE series (
    series_id serial PRIMARY KEY,
    category_id integer,
    name VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    file VARCHAR(255),
    status series_status NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK___series FOREIGN KEY (category_id) 
        REFERENCES category (category_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";

$migrationSql[] = "
CREATE INDEX idx_series_status ON series(status);
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo', 'Seria Turbo', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo Classic', 'Seria Turbo Classic', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo Sport', 'Seria Turbo Sport', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo Super', 'Seria Turbo Super', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo 2000', 'Seria Turbo 2000', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo 2014', 'Seria Turbo 2014', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo Sport 2003', 'Seria Turbo Sport 2003', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('1', 'Turbo Super 2003', 'Seria Turbo Super 2003', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('2', 'Lazer', 'Seria Lazer', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('2', 'Lazer Blue', 'Seria Lazer Blue', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('3', 'Ülker Final ''86', 'Seria Ülker Final ''86', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('3', 'Ülker Final ''88', 'Seria Ülker Final ''88', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('3', 'Ülker Final ''90', 'Seria Ülker Final ''90', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('3', 'Ülker Final ''92', 'Seria Ülker Final ''92', 'online')
";

$migrationSql[] = "
INSERT INTO series (category_id, name, description, status)
VALUES ('4', 'Otto Moto', 'Seria Otto Moto', 'online')
";
