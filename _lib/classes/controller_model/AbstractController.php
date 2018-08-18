<?php

abstract class AbstractController
{
    use Filter;
    use Http;
    use Messages;
    use Translation; 
    use SecurityToken;
    
    protected $View;
    
    public function __construct()
    {
        $this->View = View::getSingleton();
    }
}