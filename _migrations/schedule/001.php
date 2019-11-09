<?php
$migrationSql = [];

$migrationSql[] = "
CREATE TYPE schedule_status AS ENUM ('enabled', 'disabled');
";

$migrationSql[] = "
CREATE TABLE schedule (
    schedule_id serial PRIMARY KEY,
    script VARCHAR(255) UNIQUE NOT NULL,
    last_runtime TIMESTAMP DEFAULT NULL,
    next_runtime TIMESTAMP DEFAULT NULL,
    interval integer NOT NULL,
    status schedule_status NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
";

$nextRuntime = date('Y-m-d H:i:s', time() + rand(0, 86400));
$migrationSql[] = "
INSERT INTO schedule (script, interval, next_runtime, status)
VALUES ('DeleteExpiredConfirmations', '1440', '$nextRuntime', 'enabled')
";

$nextRuntime = date('Y-m-d H:i:s', time() + 300);
$migrationSql[] = "
INSERT INTO schedule (script, interval, next_runtime, status)
VALUES ('SendQueuedEmail', '1', '$nextRuntime', 'enabled')
";
