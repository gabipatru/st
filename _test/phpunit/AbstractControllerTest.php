<?php
namespace Test;

require_once(__DIR__ .'/AbstractTest.php');

/**
 * All controller tests should extend this class
 */
abstract Class AbstractControllerTest extends AbstractTest
{
    /**
     * This class will initiaalise a controller based on it's path
     */
    protected function initController(string $controllerPath, bool $mock = true)
    {
        // extract the items from the path
        $mvcMock = $this->getMockBuilder('\mvc')
                        ->disableOriginalConstructor()
                        ->setMethods([ 'serverSelf' ])
                        ->getMock();
        $mvcMock->method('serverSelf')->willReturn($controllerPath);
        list($sClass, $sPath, $sFunction) = $this->invokeMethod($mvcMock, 'extract');
        
        // load the controller file
        require_once(CONTROLLER_DIR .'/'. $sPath .'.php');
        
        // init the controller object
        $sClass = 'controller_'. $sClass;
        if ($mock) {
            $oController = $this->getMockBuilder($sClass)
                                ->setMethods([ 
                                    'isGet', 
                                    'isPost', 
                                    'filterGET', 
                                    'filterPOST', 
                                    'securityCheckToken', 
                                    'redirect',
                                    'setCookie',
                                    'validate',
                                    'deleteIsAllowed',
                                    'hrefWebsite',
                                ])
                                ->getMock();
            $oController->method('redirect')->willReturn(true);
        }
        else {
            $oController = new $sClass;
        }

        $oController->method('deleteIsAllowed')->willReturn(true);

        // we must load all translations and init the constants
        $this->loadTranslations();
        $this->initConstants();
        
        return $oController;
    }
    
    /**
     * Mock the filterPOST method to return a value from the $postValues array
     */
    protected function setPOST(array $postValues, $oMock)
    {
        $oMock->method('filterPOST')->will($this->returnCallback( function($key, $type) use ($postValues) {
            foreach ($postValues as $name => $value) {
                if ($key == $name) {
                    return $value;
                }
            }
        }));
    }

    /**
     * Mock the filterGET method to return a value from the $postValues array
     */
    protected function setGET(array $getValues, $oMock)
    {
        $oMock->method('filterGET')->will($this->returnCallback( function($key, $type) use ($getValues) {
            foreach ($getValues as $name => $value) {
                if ($key == $name) {
                    return $value;
                }
            }
        }));
    }
    
    /**
     * Mock the securityCheckToken method of the controller
     */
    protected function mockSecurityCheckToken(bool $value, $mock)
    {
        $mock->method('securityCheckToken')->willReturn($value);
    }
    
    /**
     * Mock the isPost method of the controller
     */
    protected function mockIsPost(bool $value, $mock)
    {
        $mock->method('isPost')->willReturn($value);
    }
    
    /**
     * Mock the validate method of the controller
     */
    protected function mockValidate(bool $value, $mock)
    {
        $mock->method('validate')->willReturn($value);
    }

    /**
     * Load the default translation settings for the functional tests
     */
    private function loadTranslations()
    {
        $oTranslations = \Translations::getSingleton();
        $oTranslations->resetTranslations();
        $oTranslations->setLanguage('en_EN');
        $oTranslations->setModule('common');
    }

    /**
     * Init the used constants in the functionaal tests
     */
    private function initConstants()
    {
        if (! defined('MVC_ACTION_URL')) {
            define('MVC_ACTION_URL', 'test_action');
        }
    }
}
