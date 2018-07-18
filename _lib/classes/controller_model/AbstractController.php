<?php

abstract class AbstractController
{
    use Http;
    use Messages;
    use Translation; 
    
    protected $View;
    
    public function __construct()
    {
        $this->View = View::getSingleton();
    }
}