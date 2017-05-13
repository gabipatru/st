<?php
class UserConfirmation extends DbData {
    const TABLE_NAME = 'user_confirmation';
    const ID_FIELD = 'confirmation_id';
    
    protected $aFields = array(
        'confirmation_id',
        'user_id',
        'code',
        'created_at',
        'expires_at'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
    
    public function getExpiredUserConfirmations() {
        $sql = "SELECT confirmation_id "
                ." FROM ".UserConfirmation::TABLE_NAME
                ." WHERE expires_at < NOW()";
        $res = db::query($sql);
        if (!$res || $res->errorCode() != '00000') {
            return new Collection();
        }
        
        $oCollection = new Collection();
        while ($row = db::fetchAssoc($res)) {
            $oCollection->add($row[$this->getIdField()], $row);
        }
        
        return $oCollection;
    }
}