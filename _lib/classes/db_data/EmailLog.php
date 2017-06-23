<?php

class EmailLog extends DbData {
    const TABLE_NAME     = 'email_log';
    const ID_FIELD       = 'email_log_id';
    
    const STATUS_SENT		= 'sent';
    const STATUS_NOT_SENT	= 'not sent';
    
    protected $aFields = array(
    	'email_log_id',
        'email_queue_id',
    	'status',
    	'error_info',
    	'debug',
    	'created_at'
    );
    
	function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
		parent::__construct($table, $id, $status);
	}
}