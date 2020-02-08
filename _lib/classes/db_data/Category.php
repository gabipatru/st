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

    const SERIES_ONLINE = 'online';
    const SERIEES_OFFLINE = 'offline';

    protected $elasticSearchIndex = 'category';
    protected $elasticSearchType = 'category';
    
    protected $aFields = [
        'category_id',
        'name',
        'description',
        'file',
        'status',
        'created_at'
    ];
    protected $aElasticFields = [
        'name',
        'description',
        'status'
    ];
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
}
