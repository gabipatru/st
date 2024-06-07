<?php

abstract class AbstractController
{
    use Filter;
    use Http;
    use Messages;
    use Translation; 
    use SecurityToken;
    

    protected $View;
    protected $db;
    
    public function __construct()
    {
        $this->View = View::getSingleton();
        $this->db = db::getSingleton();
    }
    
    protected function validate(FormValidation $FV) :bool
    {
        return $FV->validate();
    }

    protected function deleteIsAllowed()
    {
        return Config::configByPath(DbData::ALLOW_DELETE_KEY) === Config::CONFIG_VALUE_YES;
    }

    protected function hrefWebsite($data)
    {
        return href_website($data);
    }

    protected function hrefAdmin($data)
    {
        return href_admin($data);
    }

    protected function setCookie($name, $value, $expire, $path)
    {
        setCookie($name, $value, $expire, $path);
    }

    protected function isLoggedIn()
    {
        return User::isLoggedIn();
    }
}