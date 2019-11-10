<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE cron_run (
    cron_run_id serial PRIMARY KEY,
    cron_id integer NOT NULL,
    runtime TIMESTAMP DEFAULT NULL,
    durations integer NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK___cron_run FOREIGN KEY (cron_id) 
        REFERENCES cron(cron_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";
