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
        header('Location:'. $url);
        exit();
    }
    
    /*
     * Redirect to not found
     */
    protected function redirect404()
    {
        header("Location:".HTTP_MAIN."/website/not_found.html");
        exit();
    }
        
    protected function isLoggedIn()
    {
        return User::isLoggedIn();
    }
    
    // format a string for use in URL
    public function urlFormat(string $name) :string
    {
        $name = str_replace(' ', '-', $name);
        $name = str_replace('_', '-', $name);
        
        return $name;
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
    
    protected function JsonError(string $msg = null)
    {
        $response = [ 'response' => 'error' ];
        if ($msg) {
            $response['error_message'] = $msg;
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}