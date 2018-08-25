<?php
class controller_user extends AbstractController {
    
    function _prehook() {
        $this->View->setDecorations('website');
        
        $this->View->addCSS('/bundle.css');
        $this->View->addJS('/bundle.js');
        
        $oTranslations = Translations::getSingleton();
        $oTranslations->setModule('user');
    }
    
    function _posthook() {
    
    }
    
    ###############################################################################
    ## LOGIN PAGE
    ###############################################################################
    function login() {
        if ($this->isLoggedIn()) {
            $this->setMessage($this->__('You are already logged in !'));
            $this->redirect(href_website('website/homepage'));
        }
        
        $return = $this->filterGET('return', 'urldecode');
         
        $FV = new FormValidation(array(
            'rules' => array(
                'username' => 'required',
                'password' => 'required'
            ),
            'messages' => array(
                'username' => $this->__('You need a username or email to login'),
                'password' => $this->__('You need a password to login')
            )
         ));

        $validateResult = $FV->validate();
        if ($this->isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception($this->__('Please fill all the required fields'));
                }
                if (User::isLoggedIn()) {
                    throw new Exception($this->__('You are already logged in !'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                 
                $returnUrl = $this->filterPOST('return', 'urldecode');
                $configUserConfirmation = Config::configByPath(User::CONFIG_USER_CONFIRMATION);
                 
                $oItem = new SetterGetter();
                $oItem->setUsername($this->filterPOST('username', 'string'));
                $oItem->setPassword($this->filterPOST('password', 'string'));
                 
                $oUser = new User();
                 
                // check if the user is banned
                if ($oUser->checkUserBanned($oItem->getUsername(), $oItem->getUsername())) {
                    throw new Exception($this->__('This account is banned!'));
                }
                 
                // check if the user is active
                if ($configUserConfirmation && $oUser->checkUserInactive($oItem->getUsername(), $oItem->getUsername())) {
                    throw new Exception($this->__('You must activate your account before logging in'));
                }
                 
                $oLoggedUser = $oUser->Login($oItem);
                if (!is_object($oLoggedUser)) {
                    throw new Exception($this->__('Incorect username or password'));
                }

                $this->redirect($returnUrl ? $returnUrl : href_website('website/homepage'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
         
        $this->View->assign('return', $return);
        $this->View->assign('FV', $FV);
        
        $this->View->addSEOParams(
            $this->__('Login Page :: Surprize Turbo'),
            $this->__('Log In the Surprize Turbo website.'),
            $this->__('turbo surprises, login, exchange surprises, search turbo surprises')
        );
    }
    
    ###############################################################################
    ## LOGOUT PAGE
    ###############################################################################
    public function logout() {
        $oUser = new User();
        $oUser->logout();
        
        $this->redirect(href_website('website/homepage'));
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
                'email'         => $this->__('Please enter a valid email address'),
                'username'      => $this->__('Please choose a username, at least 2 characters long'),
                'password'      => $this->__('Password is not strong enough'),
                'password2'     => $this->__('Passwords must be identical'),
                'first_name'    => $this->__('Please fill in your first name'),
                'last_name'     => $this->__('Please fill in your last name')
            ),
        ));
        
        $userAdded = false;
        
        $validateResult = $FV->validate();
        if ($this->isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception($this->__('Please fill all the required fields'));
                }
                if (User::isLoggedIn()) {
                    throw new Exception($this->__('You are already logged in !'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                // get user-related configs
                $configUserConfirmation = Config::configByPath(User::CONFIG_USER_CONFIRMATION);
                $configWelcomeEmail		= Config::configByPath(User::CONFIG_WELCOME_EMAIL);
                
                // prepare data
                $oItem = new SetterGetter();
                $oItem->setEmail($this->filterPOST('email', 'email'));
                $oItem->setUsername($this->filterPOST('username', 'clean_html'));
                $oItem->setPassword($this->filterPOST('password', 'string'));
                $oItem->setFirstName($this->filterPOST('first_name', 'clean_html'));
                $oItem->setLastName($this->filterPOST('last_name', 'clean_html'));
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
                    throw new Exception($this->__('This username is already taken. Please choose another one'));
                }
                
                // check if another user with that email exists
                $filters = array('email' => $oItem->getEmail());
                $options = array();
                $Collection = $oUser->Get($filters, $options);
                if (count($Collection)) {
                    throw new Exception($this->__('A user with that email already exists. Please use another email'));
                }
                
                $this->db->startTransaction();
                
                // add the user
                $confirmationCode = $oUser->Add($oItem);
                if (!$confirmationCode) {
                    throw new Exception($this->__('Error adding user to the database. Please try again later'));
                }
                
                // send confirmation email if necessary
                if ($configWelcomeEmail) {
                    // create email
                    $oEmailTemplate = new EmailTemplate('newuser.php');
                    $oEmailTemplate->assign('username', $oItem->getUsername());
                    $oEmailTemplate->assign('confirmationCode', $confirmationCode);
                    
                    $r = $oEmailTemplate->queue($oItem->getEmail(), $this->__('Welcome'). ', '. $oItem->getUsername());
                    if (!$r) {
                        throw new Exception($this->__('Could not send confirmation email. Please try again later.'));
                    }
                }
                
                $this->db->commitTransaction();
                
                $this->setMessage($this->__('User added to the database'));
                $userAdded = true;
            }
            catch (Exception $e) {
                if ($this->db->transactionLevel()) {
                    $this->db->rollbackTransaction();
                }
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $this->View->assign('userAdded', $userAdded);
        $this->View->assign('FV', $FV);
        
        $this->View->addSEOParams(
            $this->__('Create user :: Surprize Turbo'),
            $this->__('Create a new user.'),
            $this->__('turbo surprises, new user, exchange surprises, search turbo surprises')
        );
    }
    
    ###############################################################################
    ## ACCOUNT CONFIRMATION PAGE
    ###############################################################################
    public function confirm() {
        $code = $this->filterGET('code', 'string');
        
        try {            
            if (empty($code)) {
                throw new Exception(
                    $this->__("Could not find the code. You need the activetion code to activate the account")
                );
            }
            
            // search for the activation code in DB
            $filters = array('code' => $code);
            $options = array();
            $oUserConf = new UserConfirmation();
            $oCode = $oUserConf->singleGet($filters, $options);
            if (! $oCode instanceof SetterGetter) {
                throw new Exception($this->__('Activation code is not correct'));
            }
            
            // activate user
            $oItem = new SetterGetter();
            $oItem->setStatus(User::STATUS_ACTIVE);
            
            $this->db->startTransaction();
            
            $oUser = new User();
            $r = $oUser->Edit($oCode->getUserId(), $oItem);
            if (!$r) {
                throw new Exception($this->__('Account could not be activated. Please try again later'));
            }
            
            // delete the old confirmation code
            $r = $oUserConf->Delete($oCode->getConfirmationId());
            if (!$r) {
                throw new Exception($this->__('Account could not be activated. Please try again later'));
            }
            
            $this->db->commitTransaction();
            
            $this->setMessage($this->__('Your account is not active'));
        }
        catch (Exception $e) {
            if ($this->db->transactionLevel()) {
                $this->db->rollbackTransaction();
            }
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->View->addSEOParams(
            $this->__('Welcome to Surprize Turbo'),
            $this->__('Welcome to Surprize Turbo where you can exchange you favorite surprises.'),
            $this->__('turbo surprises, welcome, exchange surprises, search turbo surprises')
        );
    }
    
    ###############################################################################
    ## FORGOT PASSWORD PAGE
    ###############################################################################
    function forgot_password() {
        if ($this->isLoggedIn()) {
            $this->setErrorMessage($this->__('You cannot reset your password if you are logged in'));
            $this->redirect('website/homepage');
        }
        $emailSent = false;
        
        $FV = new FormValidation(array(
            'rules' => array(
                'email' => array(
                    'required' => true, 
                    'email' => true
                ),
            ),
            'messages' => array(
                'email' => $this->__('Please enter a valid email address')
            )
        ));
        
        $validateResult = $FV->validate();
        
        if ($this->isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception($this->__('Please fill all the required fields'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                $email = $this->filterPOST('email', 'email');
                
                // check if the email exists in our database
                $oUser = new User();
                $filters = array('email' => $email);
                $options = array();
                $oCollection = $oUser->Get($filters, $options);
                if ($oCollection->getItemsNo() == 0) {
                    throw new Exception($this->__('This email does not exist in the database'));
                }
                $oItem = $oCollection->getItem();
                
                $this->db->startTransaction();
                
                // create confirmation
                $oConf = new UserConfirmation();
                $confirmationCode = $oConf->createNewConfirmation($oItem->getUserId());
                if (!$confirmationCode) {
                    throw new Exception($this->__('Could not create confirmation code'));
                }
                
                // send the email with the confirmation code
                $oEmailTemplate = new EmailTemplate('forgot_password.php');
                $oEmailTemplate->assign('confirmationCode', $confirmationCode);
                 
                $r = $oEmailTemplate->queue($email, $this->__('Reset password'), null, EmailQueue::PRIORITY_HIGHEST);
                if (!$r) {
                    throw new Exception($this->__('Could not send confirmation email. Please try again later.'));
                }
                
                $this->db->commitTransaction();
                $emailSent = true;
                $this->setMessage($this->__('An email has benn sent to your email address'));
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
                if ($this->db->transactionLevel()) {
                    $this->db->rollbackTransaction();
                }
            }
        }
        
        $this->View->assign('FV', $FV);
        $this->View->assign('emailSent', $emailSent);
        
        $this->View->addSEOParams(
            $this->__('Forgot Password :: Surprize Turbo'),
            $this->__('If you forgot your password you can reset it here.'),
            $this->__('turbo surprises, forgot password, exchange surprises, search turbo surprises')
        );
    }
    
    ###############################################################################
    ## RESET PASSWORD PAGE
    ###############################################################################
    function reset_password() {
        if ($this->isLoggedIn()) {
            $this->setErrorMessage($this->__('You cannot reset your password if you are logged in'));
            $this->redirect('website/homepage');
        }
        
        $confirmationCode = $this->filterREQUEST('code', 'string');
        $error = false;
        
        $FV = new FormValidation(array(
            'rules' => array(
                'password' => array(
                    'required' => true,
                    'minlength' => 2
                ),
                'password2' => array(
                    'required' => true,
                    'equalTo' => 'password'
                ),
            ),
            'messages' => array(
                'password'      => $this->__('Password is not strong enough'),
                'password2'     => $this->__('Passwords must be identical'),
            )
        ));
        
        $validateResult = $FV->validate();
        
        if ($this->isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception($this->__('Please fill all the required fields'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                if (!$confirmationCode) {
                    throw new Exception($this->__('Incorrect code'));
                }
                
                $newPassword = $this->filterPOST('password', 'string');
                
                // search for the code
                $filters = array('code' => $confirmationCode);
                $options = array();
                $oUserConf = new UserConfirmation();
                $oCollection = $oUserConf->Get($filters, $options);
                if ($oCollection->getItemsNo() == 0) {
                    throw new Exception($this->__('Incorrect code'));
                }
                $oCode = $oCollection->getItem();
                
                // get the user
                $oUser = new User();
                $filters = array('user_id' => $oCode->getUserId());
                $options = array();
                $oCollection = $oUser->Get($filters, $options);
                if ($oCollection->getItemsNo() == 0) {
                    throw new Exception($this->__('User not found in the database'));
                }
                $oLoadedUser = $oCollection->getItem();
                
                $this->db->startTransaction();
                
                // update the password
                $oItem = new SetterGetter();
                $oItem->setPassword(User::passwordHash($newPassword));
                $r = $oUser->Edit($oLoadedUser->getUserId(), $oItem);
                if (!$r) {
                    throw new Exception($this->__('Error while saving the new password'));
                }
                
                // delete the confirmation
                $oUserConf->Delete($oCode->getConfirmationId());
                
                $this->db->commitTransaction();
                $this->setMessage($this->__('Password was reset'));
            }
            catch (Exception $e) {
                if ($this->db->transactionLevel()) {
                    $this->db->rollbackTransaction();
                }
                $this->setErrorMessage($e->getMessage());
            }
            
            $this->redirect(href_website('user/login'));
        }
        
        try {
            if (!$confirmationCode) {
                throw new Exception($this->__('Incorrect code'));
            }
            
            // search for the code
            $filters = array('code' => $confirmationCode);
            $options = array();
            $oUserConf = new UserConfirmation();
            $oCode = $oUserConf->Get($filters, $options);
            if ($oCode->getItemsNo() == 0) {
                throw new Exception($this->__('Incorrect code'));
            }
            
            
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            $error = true;
        }
        
        $this->View->assign('error', $error);
        $this->View->assign('confirmationCode', $confirmationCode);
        $this->View->assign('FV', $FV);
        
        $this->View->addSEOParams(
            $this->__('Reset Password :: Surprize Turbo'),
            $this->__('Reset your password and start using your account again.'),
            $this->__('turbo surprises, reset password, exchange surprises, search turbo surprises')
        );
    }
}