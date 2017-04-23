<?php
class controller_user {
    
    function _prehook() {
        mvc::setDecorations('website');
        
        $oTranslations = Translations::getSingleton();
        $oTranslations->setModule('user');
    }
    
    function _posthook() {
    
    }
    
    ###############################################################################
    ## LOGIN PAGE
    ###############################################################################
    function login() {
         $FV = new FormValidation(array(
            'rules' => array(
                'username' => 'required',
                'password' => 'required'
            ),
            'messages' => array(
                'username' => __('You need a username or email to login'),
                'password' => __('You need a password to login')
            )
         ));
         
         $validateResult = $FV->validate();
         if (isPOST()) {
             try {
                 if (!$validateResult) {
                     throw new Exception(__('Please fill all the required fields'));
                 }
                 if (!securityCheckToken(filter_post('token', 'string'))) {
                     throw new Exception(__('The page delay was too long'));
                 }
                 
                 $oItem = new SetterGetter();
                 $oItem->setUsername(filter_post('username', 'string'));
                 $oItem->setPassword(filter_post('password', 'string'));
                 
                 $oUser = new User();
                 $oLoggedUser = $oUser->Login($oItem);
                 if (!is_object($oLoggedUser)) {
                     throw new Exception(__('Incorect username or password'));
                 }
                 
                 http_redir(href_website('website/homepage'));
             }
             catch (Exception $e) {
                 message_set_error($e->getMessage());
             }
         }
         
         mvc::assign('FV', $FV);
    }
    
    ###############################################################################
    ## NEW USER PAGE
    ###############################################################################
    function newuser() {
        $FV = new FormValidation(array(
            'rules' => array(
                'email' => array(
                    'required' => true, 
                    'email' => true
                ),
                'username' => array(
                    'required' => true,
                    'minlength' => 2
                ),
                'password' => array(
                    'required' => true,
                    'minlength' => 2
                ),
                'password2' => array(
                    'required' => true,
                    'equalTo' => 'password'
                ),
                'first_name' => 'required',
                'last_name' => 'required'
            ),
            'messages' => array(
                'email'         => __('Please enter a valid email address'),
                'username'      => __('Please choose a username, at least 2 characters long'),
                'password'      => __('Password is not strong enough'),
                'password2'     => __('Passwords must be identical'),
                'first_name'    => __('Please fill in your first name'),
                'last_name'     => __('Please fill in your last name')
            ),
        ));
        
        $validateResult = $FV->validate();
        if (isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception(__('Please fill all the required fields'));
                }
                
                $oItem = new SetterGetter();
                $oItem->setEmail(filter_post('email', 'email'));
                $oItem->setUsername(filter_post('username', 'clean_html'));
                $oItem->setPassword(filter_post('password', 'string'));
                $oItem->setFirstName(filter_post('first_name', 'clean_html'));
                $oItem->setLastName(filter_post('last_name', 'clean_html'));
                
                $oUser = new User();
                
                // check if another user with that username exists
                $filters = array('username' => $oItem->getUsername());
                $options = array();
                $Collection = $oUser->Get($filters, $options);
                if (count($Collection)) {
                    throw new Exception(__('This username is already taken. Please choose another one'));
                }
                
                // check if another user with that email exists
                $filters = array('email' => $oItem->getEmail());
                $options = array();
                $Collection = $oUser->Get($filters, $options);
                if (count($Collection)) {
                    throw new Exception(__('A user with that email already exists. Please use another email'));
                }
                
                // add the user
                $r = $oUser->Add($oItem);
                if (!$r) {
                    throw new Exception(__('Error adding user to the database. Please try again later'));
                }
                
                // @TODO: add confirmation if user is not active
                
                // @TODO: send welcome email
                
                message_set(__('User added to the database'));
            }
            catch (Exception $e) {
                message_set_error($e->getMessage());
            }
        }
        
        mvc::assign('FV', $FV);
    }
}