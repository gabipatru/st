<?php
class controller_website {
	
	function _prehook() {
		mvc::setDecorations('website');
		
		mvc::addCSS('/bundle.css');
		mvc::addJS('/bundle.js');
		
		$oTranslations = Translations::getSingleton();
		$oTranslations->setModule('website');
	}
	
	function _posthook() {
	
	}
	
	###############################################################################
	## THE HOMEPAGE
	###############################################################################
	function homepage() {

	}
	
	###############################################################################
	## CONTACT PAGE
	###############################################################################
    function contact() {
        $messageSent = false;
        
        $FV = new FormValidation(array(
            'rules' => array(
                'name'      => 'required',
                'email'     => array('required' => true, 'email' => true),
                'subject'   => 'required',
                'message'   => 'required'
            ),
            'messages' => array(
                'name'      => __('Please fill in your name'),
                'email'     => __('Please enter a valid email address'),
                'subject'   => __('Please specify a subject'),
                'message'   => __('Please write the message we should receive')
            )
        ));
        
        $validateResult = $FV->validate();
        
        if (isPOST()) {
            try {
                throw new Exception('test div');
                if (!$validateResult) {
                    throw new Exception(__('Please make sure you filled all the required fields'));
                }
                if (!securityCheckToken(filter_post('token', 'string'))) {
                    throw new Exception(__('The page delay was too long'));
                }
                
                $name       = filter_post('name', 'string');
                $email      = filter_post('email', 'email');
                $subject    = filter_post('subject', 'string');
                $message    = filter_post('message', 'string');
                
                // send the email
                $oEmailTemplate = new EmailTemplate('contact.php');
                $oEmailTemplate->assign('name', $name);
                $oEmailTemplate->assign('email', $email);
                $oEmailTemplate->assign('subject', $subject);
                $oEmailTemplate->assign('message', $message);
                
                $toEmail = Config::configByPath('/Email/Email Sending/Contact Email');
                 
                $r = $oEmailTemplate->send($toEmail, $subject);
                if (!$r) {
                    throw new Exception(__('Could not send email. Please try again later.'));
                }
                
                $messageSent = true;
            }
            catch (Exception $e) {
                message_set_error($e->getMessage());
            }
        }
        
        mvc::assign('FV', $FV);
        mvc::assign('messageSent', $messageSent);
    }
    
    public function save_language() {
        $newLanguage = filter_get('language', 'string');
        $referrer = filter_get('referrer', 'string');
        
        if (!$referrer) {
            $referrer = href_website('website/homepage');
        }
        
        $oTranslations = Translations::getSingleton();
        if (!$newLanguage || !$oTranslations->checkIfLanguageExists($newLanguage)) {
            message_set_error(__('Could not configure language'));
            http_redir($referrer);
        }
        
        setcookie(Translations::COOKIE_NAME, $newLanguage, time() + 86400 * 365, "/");
        
        http_redir($referrer);
    }
}
