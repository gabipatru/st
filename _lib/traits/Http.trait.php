<?php

/*
 * This trait privedes some useful functions for Http
 */
trait Http
{
    /*
     * Redirect to a URL and stop the script execution
     */
    protected function redirect(string $url)
    {
        header('Location:'.$this->urlFormat($url));
        exit();
    }
    
    /*
     * Redirect to not found
     */
    protected function redirect404()
    {
        header("Location:".HTTP_BASE_URL."404.php");
        exit();
    }
    
    /*
     * Standard format for the url
     */
    protected function urlFormat(string $str) :string
    {
        $str = str_replace(' ', '_', $str);
        $str = str_replace('-', '_', $str);
        return $str;
    }
        
    protected function isLoggedIn()
    {
        return User::isLoggedIn();
    }
    
    ###############################################################################
    ## FUNCTIONS FOR CHEKING HTTP VERBS
    ###############################################################################
    protected function isGET() :bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'GET');
    }
    
    protected function isPOST() :bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }
    
    ###############################################################################
    ## FUNCTIONS FOR OUTPUTTING JSON
    ###############################################################################
    protected function JsonSuccess()
    {
        header('Content-Type: application/json');
        echo json_encode([ 'response' => 'success' ]);
    }
    
    protected function JsonError(?string $msg = null)
    {
        $response = [ 'response' => 'error' ];
        if ($msg) {
            $response['error_message'] = $msg;
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}