<?php

abstract class AbstractController
{
    use Translation; 
    
    protected $View;
    
    public function __construct()
    {
        $this->View = View::getSingleton();
    }
}