<?php
class controller_user {
    
    function _prehook() {
        mvc::setDecorations('website');
    }
    
    function _posthook() {
    
    }
    
    ###############################################################################
    ## LOGIN PAGE
    ###############################################################################
    function login() {
         
    }
    
    function newuser() {
        $FV = new FormValidation(array(
            'rules' => array(
                'email' => array(
                    'required' => true, 
                    'email' => true
                ),
                'username' => array(
                    'required',
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
                'email'         => _('Please fill a valid email address'),
                'username'      => _('Please choose a username, at least 2 characters long'),
                'password'      => _('Password is not strong enough'),
                'password2'     => _('Passwords must be identical'),
                'first_name'    => _('Please fill in your first name'),
                'last_name'     => _('Please fill in your last name')
            ),
        ));
        
        $validateResult = $FV->validate();
        if (isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception(_('Please fill all the required fields'));
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
                    throw new Exception(_('This username is already taken. Please choose another'));
                }
                
                // check if another user with that email exists
                $filters = array('email' => $oItem->getEmail());
                $options = array();
                $Collection = $oUser->Get($filters, $options);
                if (count($Collection)) {
                    throw new Exception(_('A user with that email already exists. Please use another email'));
                }
                
                // add the user
                $r = $oUser->Add($oItem);
                if (!$r) {
                    throw new Exception('Error adding user to the database. Please try again later');
                }
                
                // @TODO: add confirmation if user is not active
                
                // @TODO: send welcome email
                
                message_set(_('User added to the database'));
            }
            catch (Exception $e) {
                message_set_error($e->getMessage());
            }
        }
        
        mvc::assign('FV', $FV);
    }
}