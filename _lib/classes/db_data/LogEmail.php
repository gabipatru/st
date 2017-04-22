<?php

class LogEmail extends DbData {
    const TABLE_NAME     = 'log_email';
    const ID_FIELD       = 'id';
    
    // @TODO: add fields
    
	function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
		parent::__construct($table, $id, $status);
	}
}