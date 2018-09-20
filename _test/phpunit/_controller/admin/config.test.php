<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../../AbstractControllerTest.php');

class controller_admin_config extends AbstractControllerTest
{
    public function setUp()
    {
        parent::setUp();
        
        // load translations
        $oTranslations = \Translations::getSingleton();
        $oTranslations->resetTranslations();
        $oTranslations->setLanguage('en_EN');
        $oTranslations->setModule('common');
    }
    
    /**
     * Test what happens if the request is not post
     * @group slow
     */
    public function test_add_not_post()
    {
        $this->setUpDB([ 'config' ]);
        
        $oMockController = $this->initController('/admin/index.php/admin/config/add');
        $this->mockIsPost(false, $oMockController);
        $oMockController->expects($this->never())->method('validate');
        
        $oConfigModel = new \Config();
        $oConfig = $oConfigModel->Get();
        
        $oMockController->add();
        
        $oNewConfig = $oConfigModel->Get();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // assert
        $this->assertEquals(count($oConfig), count($oNewConfig));
    }
    
    /**
     * Test what happens if the form does not validate
     * @group fast
     */
    public function test_add_invalid_validation()
    {
        $oMockController = $this->initController('/admin/index.php/admin/config/add');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(false, $oMockController);
        
        // the test
        $oMockController->add();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Please make sure you filled all mandatory values', array_keys($messages)));
    }
    
    /**
     * Test what happens if the security token is invalid
     * @group fast
     */
    public function test_add_invalid_token()
    {
        $oMockController = $this->initController('/admin/index.php/admin/config/add');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);
        
        // the test
        $oMockController->add();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }
    
    /**
     * Try adding a config with correct data
     * @group slow
     * @depends test_add_not_post
     */
    public function test_add()
    {
        // clear memcache
        $oMemcache = \Mcache::getSingleton();
        $oMemcache->delete(\Config::MEMCACHE_KEY);
        
        $oMockController = $this->initController('/admin/index.php/admin/config/add');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        
        $this->setPOST([ 'path' => 'test/test/test', 'value' => 'test', 'type' => 'text' ], $oMockController);
        
        $oConfigModel = new \Config();
        $oConfig = $oConfigModel->Get();

        // the test
        $oMockController->add();
        
        $oRegistry = \Registry::getSingleton();
        $oRegistry->set(\Config::REGISTRY_KEY, null);
        
        $oNewConfig = $oConfigModel->Get();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // assert
        $this->assertEquals(count($oConfig) + 1, count($oNewConfig));
        $this->assertTrue(is_array($messages));
        $this->assertTrue(in_array('Config added successfully', array_keys($messages)));
    }
}