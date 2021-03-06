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
            return $this->redirect404();
        }
        
        // load current category
        $oCategoryModel = new Category();
        $filter = [ 'category_id' => $categoryId, 'status' => 'online' ];
        $oCategory = $oCategoryModel->singleGet($filter);
        if (! $oCategory) {
            return $this->redirect404();
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
    ## SERIES PAGE
    ###############################################################################
    function series()
    {
        $seriesId           = $this->filterGET('series_id', 'int');
        $seriesNameFromUrl  = $this->filterGET('series_name', 'string');
        if (! $seriesId || ! $seriesNameFromUrl) {
            return $this->redirect404();
        }
        
        // load the series
        $oSeriesModel = new Series();
        $filter = [ 'series_id' => $seriesId, 'status' => 'online' ];
        $oSeries = $oSeriesModel->singleGet($filter);
        if (! $oSeries) {
            return $this->redirect404();
        }
        if ($this->View->urlFormat($oSeries->getName()) != $seriesNameFromUrl) {
            return $this->redirect404();
        }

        try {
            $oSurpriseCollection = null;

            // load the groups of the series
            $oGroupModel = new Group();
            $filter = [ 'series_id' => $seriesId ];
            $oGroupCollection = $oGroupModel->Get($filter);
            if (! $oGroupCollection->count()) {
                throw new Exception($this->___('Could not find any groups for the series %s', $oSeries->getName()));
            }
            
            // load the surprises
            $aGroupIds = [];
            foreach ($oGroupCollection as $group) {
                $aGroupIds[] = $group->getGroupId();
            }
            
            $oSurpriseModel = new Surprise();
            $filter = [ 'group_id' => $aGroupIds ];
            $oSurpriseCollection = $oSurpriseModel->Get($filter);
            if (! $oSurpriseCollection->count()) {
                throw new Exception($this->___('Could not find any surprises for the series %s', $oSeries->getName()));
            }
        }
        catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
        }
        
        $this->View->assign('oSeries', $oSeries);
        $this->View->assign('oGroupCollection', $oGroupCollection);
        $this->View->assign('oSurpriseCollection', $oSurpriseCollection);
        
        $this->View->addSEOParams(
            $this->___('Surprize Turbo: %s Series', $oSeries->getName()),
            $this->___('Check out the surprises of the series %s', $oSeries->getName()),
            $this->__('turbo surprises, exchange surprises, search turbo surprises') .', '.$oSeries->getName()
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

        $validateResult = $this->validate($FV);

        if ($this->isPOST()) {
            try {
                if (! $validateResult) {
                    throw new Exception($this->__('Please make sure you filled all the required fields'));
                }
                if (! $this->securityCheckToken($this->filterPOST('token', 'string'))) {
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
            $referrer = $this->hrefWebsite('website/homepage');
        }
        
        $oTranslations = Translations::getSingleton();
        if (!$newLanguage || !$oTranslations->checkIfLanguageExists($newLanguage)) {
            $this->setErrorMessage($this->__('Could not configure language'));
            $this->redirect($referrer);
        }
        
        $this->setCookie(Translations::COOKIE_NAME, $newLanguage, time() + 86400 * 365, "/");
        
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
