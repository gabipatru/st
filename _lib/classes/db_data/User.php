<?php

/*
 * This class handles user operations
 */

class User extends DbData {
    const TABLE_NAME    = 'user';
    const ID_FIELD      = 'user_id';
    
    const REGISTRY_KEY  = 'LOGGED_USER';
    
    protected $aFields = array(
        'user_id',
        'email',
        'username',
        'password',
        'first_name',
        'last_name',
        'status',
        'is_admin',
        'last_login',
        'created_at'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
    
    protected function onBeforeAdd($oItem) {
        $oItem->setPassword(self::passwordHash($oItem->getPassword()));
        return true;
    }
    
    public static function passwordHash($password) {
        return sha1($password . WEBSITE_SALT);
    }
    
    public static function theId() {
        if (!empty($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
        
        return false;
    }
    
    public static function theUser() {
        $oRegsitry = Registry::getSingleton();
        $oUser = $oRegsitry->get(self::REGISTRY_KEY);
        if (is_object($oUser)) {
            return $oUser;
        }
        
        return false;
    }
    
    public static function isLoggedIn() {
        $userId = self::theId();
        return ($userId ? true : false);
    }
    
    public function Login($oItem) {
        if (!is_object($oItem)) {
            return false;
        }
        
        if (self::isLoggedIn()) {
            return true;
        }
        
        // check if there is any password
        $password = $oItem->getPassword();
        if (!$password) {
            return false;
        }
        
        // passwords are stored in hash mode
        $password = self::passwordHash($password);
        
        // search for the user
        $sql = "SELECT * FROM ". self::TABLE_NAME
                ." WHERE password = ?"
                ." AND (username = ? OR email = ?)";
        $aParams = array($password, $oItem->getUsername(), $oItem->getUsername());
        $res = db::query($sql, $aParams);
        if (!$res || $res->errorCode() != '00000' || db::rowCount($res) == 0) {
            return false;
        }
        
        $lastLogin = date('Y-m-d H:i:s');
        
        // build the user object
        $row = db::fetchAssoc($res);
        $oLoggedInUser = new SetterGetter();
        $oLoggedInUser->setUserId($row['user_id']);
        $oLoggedInUser->setEmail($row['email']);
        $oLoggedInUser->setUsername($row['username']);
        $oLoggedInUser->setFirstName($row['first_name']);
        $oLoggedInUser->setLastName($row['last_name']);
        $oLoggedInUser->setLastLogin($lastLogin);
        $oLoggedInUser->setIsAdmin($row['is_admin']);
        $oLoggedInUser->setCreatedAt($row['created_at']);
        
        // save to session
        $_SESSION['user_id']   = $oLoggedInUser->getUserId();
        $_SESSION['user_data'] = serialize($oLoggedInUser);
        
        // update last login
        $oUpdate = new SetterGetter();
        $oUpdate->setLastLogin($lastLogin);
        $this->Edit($oLoggedInUser->getUserId(), $oUpdate);
        
        return $oLoggedInUser;
    }
    
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_data']);
    }
}