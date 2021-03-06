<?php
$migrationSql = array();

$migrationSql[] = "DROP TYPE IF EXISTS config_type";

$migrationSql[] = "
CREATE TYPE config_type AS ENUM ('text', 'textarea', 'yesno');
";

$migrationSql[] = "
CREATE TABLE config(
    config_id serial PRIMARY KEY,
    path VARCHAR(255) UNIQUE NOT NULL,
    value TEXT,
    type config_type
);
";

// add config for User ACL
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/ACL/Enable ACL', '1', 'yesno');
";

// add config for Content Security Policy
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Security/Content Security Policy', 'default-src ''self'' ''unsafe-inline'' ''nonce-29af2i'' data:', 'textarea');
";

// add config for user activation control
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Users/Enable User Confirmation', '1', 'yesno');
";

// add config for confirmation code expiry
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Users/Confirmation expiry', '1 day', 'text');
";

// add config for welcome email to a new user
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Users/Welcome Email', '1', 'yesno');
";

// limit the maximum number of confirmations
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Users/Max confirmations per user', '8', 'text');
";

// add config for global email_form and email_from_name
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Email/Email Sending/Email From', 'website@mvc.ro', 'text');
";
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Email/Email Sending/Email From Name', 'Website', 'text');
";
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Email/Email Sending/Number of tries', '3', 'text');
";
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Email/Email Sending/Number of emails to send in one go', '5', 'text');
";

// add config for pagination
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Pagination/Per Page', '20', 'text');
";

// add config for database deletion
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Website/Database/Delete permitted', '0', 'yesno');
";

// add config for phone number
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES
('HTML/Header/Phone Number EN', '+44 7397 030 770', 'text'),
('HTML/Header/Phone Number RO', '+40 766 248 430', 'text')
";

// add config for address
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES
('HTML/Contact Page/Address EN', '16 Birkbeck Road, N17 8NG', 'text'),
('HTML/Contact Page/Address RO', 'Str Turda Nr 100 Bl 30B Sc A Et 4 Ap 13, Sector 1', 'text')
";
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES
('HTML/Contact Page/City EN', 'London, United Kingdom', 'text'),
('HTML/Contact Page/City RO', 'Bucuresti, Romania', 'text')
";

// add config for HTML footer debug info
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES
('HTML/Footer Debug/Dsiplay in admin', '1', 'yesno'),
('HTML/Footer Debug/Display in website', '1', 'yesno')
";

// add config for contact email
$migrationSql[] = "
INSERT INTO config (path, value, type)
VALUES ('/Email/Email Sending/Contact Email', 'contact@surptizeturbo.ro', 'text');
";
