<?php
$migrationSql = array();

$migrationSql[] = "DROP TYPE IF EXISTS email_queue_status";

$migrationSql[] = "
CREATE TYPE email_queue_status AS ENUM ('sent', 'not sent');
";

$migrationSql[] = "
CREATE TABLE email_queue (
  email_queue_id serial PRIMARY KEY,
  too VARCHAR(255) NOT NULL,
  subject VARCHAR(255),
  body TEXT,
  priority integer NOT NULL DEFAULT 10,
  status email_queue_status NOT NULL DEFAULT 'not sent',
  send_attempts integer NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL
);
";

$migrationSql[] = "
CREATE INDEX idx_email_queue_status_send_attempts ON email_queue(status, send_attempts);
";

$migrationSql[] = "
CREATE TABLE email_log (
  email_log_id serial PRIMARY KEY,
  email_queue_id integer DEFAULT NULL,
  status email_queue_status NOT NULL,
  error_info TEXT,
  debug TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT FK___email_log FOREIGN KEY (email_queue_id) 
    REFERENCES email_queue(email_queue_id)
);
";
