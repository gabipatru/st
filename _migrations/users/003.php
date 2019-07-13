<?php
$migrationSql = array();

$migrationSql[] = "
CREATE TABLE user_confirmation(
  confirmation_id serial PRIMARY KEY,
  user_id integer NOT NULL,
  code CHAR(32),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT FK___user_confirmation FOREIGN KEY (user_id) 
    REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE
);
";

$migrationSql[] = "
CREATE INDEX idx_user_confirmation_code ON user_confirmation(code);
";