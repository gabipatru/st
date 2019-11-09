<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TABLE schedule_run (
    schedule_run_id serial PRIMARY KEY,
    schedule_id integer NOT NULL,
    runtime TIMESTAMP DEFAULT NULL,
    durations integer NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK___schedule_run FOREIGN KEY (schedule_id) 
        REFERENCES schedule(schedule_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";
