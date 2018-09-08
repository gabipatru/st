<?php

/*
 * CRUD for category table
 */
class Category extends DbData
{
    const TABLE_NAME    = 'category';
    const ID_FIELD      = 'category_id';
    
    const UPLOAD_DIR    = IMAGES_DIR .'/categories';
    const HTTP_DIR      = HTTP_FILE_IMAGES .'/categories';
    
    const IMAGE_WIDTH   = 250;
    const IMAGE_HEIGHT  = 250;
    
    protected $aFields = array(
        'category_id',
        'name',
        'description',
        'file',
        'status',
        'created_at'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
}