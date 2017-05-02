<?php
class controller_user {
    
    function _prehook() {
        mvc::setDecorations('website');
        
        mvc::addCSS('/bundle.css');
        mvc::addJS('/bundle.js');
        
        $oTranslations = Translations::getSingleton();
        $oTranslations->setModule('user');
    }
    
    function _posthook() {
    
    }
    
    ###############################################################################
    ## LOGIN PAGE, LOGOUT
    ###############################################################################
    function login() {
		$return = filter_get('return', 'urldecode');
    	 
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
                if (User::isLoggedIn()) {
                    throw new Exception('You are already logged in !');
                }
                if (!securityCheckToken(filter_post('token', 'string'))) {
                    throw new Exception(__('The page delay was too long'));
                }
                 
                $returnUrl = filter_post('return', 'urldecode');
                $configUserConfirmation = Config::configByPath(User::CONFIG_USER_CONFIRMATION);
                 
                $oItem = new SetterGetter();
                $oItem->setUsername(filter_post('username', 'string'));
                $oItem->setPassword(filter_post('password', 'string'));
                 
                $oUser = new User();
                 
                // check if the user is banned
                if ($oUser->checkUserBanned($oItem->getUsername(), $oItem->getUsername())) {
                	throw new Exception(__('This account is banned!'));
                }
                 
                // check if the user is active
                if ($configUserConfirmation && $oUser->checkUserInactive($oItem->getUsername(), $oItem->getUsername())) {
                	throw new Exception(__('You must activate your account before logging in'));
                }
                 
                $oLoggedUser = $oUser->Login($oItem);
                if (!is_object($oLoggedUser)) {
                    throw new Exception(__('Incorect username or password'));
                }

                http_redir($returnUrl ? $returnUrl : href_website('website/homepage'));
            }
            catch (Exception $e) {
                message_set_error($e->getMessage());
            }
        }
         
        mvc::assign('return', $return);
        mvc::assign('FV', $FV);
    }
    
    public function logout() {
        $oUser = new User();
        $oUser->logout();
        
        http_redir(href_website('website/homepage'));
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
                if (User::isLoggedIn()) {
                    throw new Exception('You are already logged in !');
                }
                if (!securityCheckToken(filter_post('token', 'string'))) {
                    throw new Exception(__('The page delay was too long'));
                }
                
                // get user-related configs
                $configUserConfirmation = Config::configByPath(User::CONFIG_USER_CONFIRMATION);
                $configWelcomeEmail		= Config::configByPath(User::CONFIG_WELCOME_EMAIL);
                
                // prepare data
                $oItem = new SetterGetter();
                $oItem->setEmail(filter_post('email', 'email'));
                $oItem->setUsername(filter_post('username', 'clean_html'));
                $oItem->setPassword(filter_post('password', 'string'));
                $oItem->setFirstName(filter_post('first_name', 'clean_html'));
                $oItem->setLastName(filter_post('last_name', 'clean_html'));
                if ($configUserConfirmation) {
                    $oItem->setStatus(User::STATUS_NEW);
                }
                else {
                    $oItem->setStatus(User::STATUS_ACTIVE);
                }
                                
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
                
                db::startTransaction();
                
                // add the user
                $r = $oUser->Add($oItem);
                if (!$r) {
                    throw new Exception(__('Error adding user to the database. Please try again later'));
                }
                
                // send confirmation email if necessary
                if ($configWelcomeEmail) {
                	require_once(FUNCTIONS_DIR . '/email.php');
                	
                	// create email
                	$oEmailTemplate = new EmailTemplate('newuser.php');
                	$oEmailTemplate->assign('username', $oItem->getUsername());
                	
                	$r = $oEmailTemplate->send($oItem->getEmail(), 'Welcome, '. $oItem->getUsername());
                	if (!$r) {
                		throw new Exception(__('Could not send confirmation email. Please try again later.'));
                	}
                }
                
                db::commitTransaction();
                
                message_set(__('User added to the database'));
            }
            catch (Exception $e) {
                if (db::transactionLevel()) {
                    db::rollbackTransaction();
                }
                message_set_error($e->getMessage());
            }
        }
        
        mvc::assign('FV', $FV);
    }
    
    ###############################################################################
    ## ACCOUNT CONFIRMATION PAGE
    ###############################################################################
    public function confirm() {
        $code = filter_get('code', 'string');
        
        try {            
            if (empty($code)) {
                throw new Exception(__("Could not find the code. You need the activetion code to activate the account"));
            }
            
            // search for the activation code in DB
            $filters = array('code' => $code);
            $options = array();
            $oUserConf = new UserConfirmation();
            $oCode = $oUserConf->singleGet($filters, $options);
            if (!count($oCode)) {
                throw new Exception(__('Activation code is not correct'));
            }
            
            // activate user
            $oItem = new SetterGetter();
            $oItem->setStatus(User::STATUS_ACTIVE);
            
            db::startTransaction();
            
            $oUser = new User();
            $r = $oUser->Edit($oCode->getUserId(), $oItem);
            if (!$r) {
                throw new Exception(__('Account could not be activated. Please try again later'));
            }
            
            // delete the old confirmation code
            $r = $oUserConf->Delete($oCode->getConfirmationId());
            if (!$r) {
                throw new Exception(__('Account could not be activated. Please try again later'));
            }
            
            db::commitTransaction();
            
            message_set(__('Your account is not active'));
        }
        catch (Exception $e) {
            if (db::transactionLevel()) {
                db::rollbackTransaction();
            }
            message_set_error($e->getMessage());
        }
        
    }
}