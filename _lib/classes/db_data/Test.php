<?php

class Test extends DbData 
{
    const TABLE_NAME     = 'test';
    const ID_FIELD       = 'test_id';
    
    protected $aFields = array(
        'test_id',
        'name'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
}