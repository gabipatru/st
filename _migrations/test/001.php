<?php
$migrationSql = [];
$migrationSql[] = "
CREATE TABLE test (
	test_id serial PRIMARY KEY,
	name VARCHAR(255)
);
";