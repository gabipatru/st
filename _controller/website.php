<?php
class controller_website extends AbstractController {
    
    function _prehook() {
        $this->View->setDecorations('website');

        $this->View->addCSS('/bundle.css');
        $this->View->addJS('/bundle.js');
        
        $oTranslations = Translations::getSingleton();
        $oTranslations->setModule('website');
    }
    
    function _posthook() {
    
    }
    
    ###############################################################################
    ## THE HOMEPAGE
    ###############################################################################
    function homepage() {
        // get the categories
        $oCategoryModel = new Category();
        $oCategoriesCollection = $oCategoryModel->Get();
        
        $this->View->assign('oCategoriesCollection', $oCategoriesCollection);
        
        $this->View->addSEOParams(
            $this->__('Surprize Turbo: Comunity of Turbo Surprises fans'),
            $this->__('The largest protal for Turbo surprises fans.'),
            $this->__('turbo surprises, exchange surprises, search turbo surprises')
        );
    }
    
    ###############################################################################
    ## CATEGORY PAGE
    ###############################################################################
    function category()
    {
        $categoryId = $this->filterGET('category_id', 'int');
        if (!$categoryId) {
            $this->redirect404();
        }
        
        // load current category
        $oCategoryModel = new Category();
        $filter = ['category_id' => $categoryId];
        $oCategory = $oCategoryModel->singleGet($filter);
        if (! $oCategory) {
            $this->redirect404();
        }
        
        // load series
        $oSeriesModel = new Series();
        $filter = ['category_id' => $categoryId];
        $oSeriesCollection = $oSeriesModel->Get($filter);
        
        $this->View->assign('oCategory', $oCategory);
        $this->View->assign('oSeriesCollection', $oSeriesCollection);
        
        $this->View->addSEOParams(
            $this->___('Surprize Turbo: %s Category', $oCategory->getName()),
            $this->___('Check out the series of the category %s', $oCategory->getName()),
            $this->__('turbo surprises, exchange surprises, search turbo surprises') .', '.$oCategory->getName()
        );
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
                'name'      => $this->__('Please fill in your name'),
                'email'     => $this->__('Please enter a valid email address'),
                'subject'   => $this->__('Please specify a subject'),
                'message'   => $this->__('Please write the message we should receive')
            )
        ));
        
        $validateResult = $FV->validate();
        
        if ($this->isPOST()) {
            try {
                if (!$validateResult) {
                    throw new Exception($this->__('Please make sure you filled all the required fields'));
                }
                if (!$this->securityCheckToken($this->filterPOST('token', 'string'))) {
                    throw new Exception($this->__('The page delay was too long'));
                }
                
                $name       = $this->filterPOST('name', 'string');
                $email      = $this->filterPOST('email', 'email');
                $subject    = $this->filterPOST('subject', 'string');
                $message    = $this->filterPOST('message', 'string');
                
                // send the email
                $oEmailTemplate = new EmailTemplate('contact.php');
                $oEmailTemplate->assign('name', $name);
                $oEmailTemplate->assign('email', $email);
                $oEmailTemplate->assign('subject', $subject);
                $oEmailTemplate->assign('message', $message);
                
                $toEmail = Config::configByPath('/Email/Email Sending/Contact Email');
                 
                $r = $oEmailTemplate->queue($toEmail, $subject);
                if (!$r) {
                    throw new Exception($this->__('Could not send email. Please try again later.'));
                }
                
                $messageSent = true;
            }
            catch (Exception $e) {
                $this->setErrorMessage($e->getMessage());
            }
        }
        
        $this->View->assign('FV', $FV);
        $this->View->assign('messageSent', $messageSent);
        
        $this->View->addSEOParams(
            $this->__('Contact us :: Suprirze Turbo'),
            $this->__('Contact us at the largest protal for Turbo surprises fans.'),
            $this->__('contact turbo surprises, exchange surprises, search turbo surprises')
        );
    }
    
    ###############################################################################
    ## CHANGE LANGUAGE ACTION
    ###############################################################################
    public function save_language() {
        $newLanguage = $this->filterGET('language', 'string');
        $referrer = $this->filterGET('referrer', 'string');
        
        if (!$referrer) {
            $referrer = $this->redirect('website/homepage');
        }
        
        $oTranslations = Translations::getSingleton();
        if (!$newLanguage || !$oTranslations->checkIfLanguageExists($newLanguage)) {
            $this->setErrorMessage($this->__('Could not configure language'));
            $this->redirect($referrer);
        }
        
        setcookie(Translations::COOKIE_NAME, $newLanguage, time() + 86400 * 365, "/");
        
        $this->redirect($referrer);
    }
    
    ###############################################################################
    ## 404 Not Found
    ###############################################################################
    function not_found()
    {
        $this->View->addSEOParams(
            $this->___('Surprize Turbo: Not Found'),
            $this->___('Not found'),
            $this->__('turbo surprises, not found')
        );
    }
}
