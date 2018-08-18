<?php

/*
 * This trait provides functions to generate, get and update a token agains CSRF attacks
 */
trait SecurityToken
{
    function securityGetToken() {
        if (isset($_SESSION['security_token']['value'])) {
            return $_SESSION['security_token']['value'];
        }
        else {
            return md5(rand(0, 99999));
        }
    }
    
    function securityCheckToken($sToken) {
        if ($sToken == $this->securityGetToken()) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function securityUpdateToken() {
        $tokenExpiration = 180;
        
        // check if the token needs updating
        if (isset($_SESSION['security_token']['time']) && $_SESSION['security_token']['time'] + $tokenExpiration > time()) {
            $_SESSION['security_token']['time'] = time();
            return;
        }
        
        // update the token
        $_SESSION['security_token'] = array();
        $_SESSION['security_token']['value'] = md5(time());
        $_SESSION['security_token']['time'] = time();
    }
}