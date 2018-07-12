<?php

abstract class AbstractController
{
    protected $View;
    
    public function __construct()
    {
        $this->View = View::getSingleton();
    }
}