<?php

abstract class AbstractController
{
    use Filter;
    use Http;
    use Messages;
    use Translation; 
    use SecurityToken;
    
    /** @var View */
    protected $View;
    /** @var db */
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
}