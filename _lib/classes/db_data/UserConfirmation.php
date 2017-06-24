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
    
    /*
     * Get the number of confirmations of a user
     */
    public function UserConfirmationNumber($userId) {
        if (!$userId || !ctype_digit((string) $userId)) {
            return false;
        }
        
        $sql = " SELECT COUNT(*) AS cnt"
              ." FROM ".UserConfirmation::TABLE_NAME
              ." WHERE user_id = ?";
        $res = db::query($sql, array($userId));
        if (!$res) {
            return false;
        }
        $row = db::fetchAssoc($res);
        $number = $row['cnt'];
        return $number;
    }
    
    /*
     * Add a user confirmation code to the database while checking if the user has reached the max number of confirmations
     * 
     * @throw Exception
     */
    public function createNewConfirmation($userId) {
        if (!$userId || !ctype_digit((string) $userId)) {
            return false;
        }
        
        $configConfirmationExp  = Config::configByPath(User::CONFIG_CONFIRMATION_EXPIRY);
        $configMaxConfirmations = Config::configByPath(User::CONFIG_MAX_CONFIRMATIONS);
        
        // check if the user has reached the max number of confirmations
        $numberOfConfirmations = $this->UserConfirmationNumber($userId);
        if (!ctype_digit((string) $numberOfConfirmations)) {
            throw new Exception(__('Could not create confirmation code'));
        }
        if ($numberOfConfirmations >= $configMaxConfirmations) {
            throw new Exception(__('You cannot create any more confirmation codes'));
        }
        
        $code = md5(date('Y-m-d H:i:s') . WEBSITE_SALT);
        $oItem = new SetterGetter();
        $oItem->setUserId($userId);
        $oItem->setCode($code);
        $oItem->setExpiresAt(date('Y-m-d H:i:s', strtotime($configConfirmationExp)));
        
        $r = $this->Add($oItem);
        if (!$r) {
            return false;
        }
        
        return $code;
    }
}