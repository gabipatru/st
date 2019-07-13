<?php
$migrationSql = array();
$migrationSql[] = "
CREATE TABLE _migration (
	migration_id serial PRIMARY KEY,
	name VARCHAR(255) UNIQUE NOT NULL,
	version VARCHAR(255) NOT NULL
);
";

$migrationSql[] = "
CREATE TABLE migration_log (
	migration_log_id serial PRIMARY KEY,
	migration_id integer NOT NULL,
	query TEXT NOT NULL,
    duration numeric(10,3) NULL DEFAULT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT FK___migration FOREIGN KEY (migration_id) 
	    REFERENCES _migration (migration_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";
