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
}