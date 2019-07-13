<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../../AbstractControllerTest.php');

class controller_admin_config extends AbstractControllerTest
{
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
     * Test what happens if the config path is invalid
     * @group fast
     */
    public function test_add_invalid_config_path()
    {
        $oMockController = $this->initController('/admin/index.php/admin/config/add');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        
        $this->setPOST([ 'path' => 'test/test', 'value' => 'test', 'type' => 'text' ], $oMockController);
        
        // the test
        $oMockController->add();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('You did not write the config properly', array_keys($messages)));
    }
    
    /**
     * Test what happens if we try to add a config which has the same path as another config
     * @group fast
     */
    public function test_add_duplicate_path()
    {
        $oMockController = $this->initController('/admin/index.php/admin/config/add');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        
        $this->setPOST(['path'=>'/Website/Pagination/Per Page', 'value'=>'test', 'type'=>'text'], $oMockController);
        
        // the test
        $oMockController->add();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('A config with that path already exists', array_keys($messages)));
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
    
    /**
     * Test what happens when the config ids are not present
     * @group fast
     */
    public function test_save_all_invalid_config_ids()
    {
        $oMockController = $this->initController('/admin/index.php/admin/config/save_all');
        $this->setPOST([ 'config_ids' => null ], $oMockController);
        
        // the test
        $oMockController->save_all();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Incorrect input of config ids!', array_keys($messages)));
    }
    
    /**
     * Test what happens when the token is invalid
     * @group fast
     */
    public function test_save_all_invalid_token()
    {
        $oMockController = $this->initController('/admin/index.php/admin/config/save_all');
        $this->setPOST([ 'config_ids' => [1] ], $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);
        
        // the test
        $oMockController->save_all();
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }
    
    /**
     * Test saving new values for configs
     * @group slow
     * @depends test_add_not_post
     */
    public function test_save_all()
    {
        $oMockController = $this->initController('/admin/index.php/admin/config/save_all');
        $this->mockSecurityCheckToken(true, $oMockController);
        
        $this->setPOST([ 'config_ids' => [4, 7], 'config4' => 'test', 'config7' => 'test' ], $oMockController);
        
        $oConfigModel = new \Config();
        $oConfigModel->reInit();
        
        $value1 = $oConfigModel->configByPath('/Website/Users/Confirmation expiry');
        $value2 = $oConfigModel->configByPath('/Email/Email Sending/Email From');
        
        // the test
        $oMockController->save_all();
        
        $oConfigModel->reInit();
        $newValue1 = $oConfigModel->configByPath('/Website/Users/Confirmation expiry');
        $newValue2 = $oConfigModel->configByPath('/Email/Email Sending/Email From');
        
        $messages = $this->invokeMethod($oMockController, 'getMessages', []);
        
        // asserts
        $this->assertEquals('1 day', $value1);
        $this->assertEquals('website@mvc.ro', $value2);
        
        $this->assertEquals('test', $newValue1);
        $this->assertEquals('test', $newValue2);
        
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('All items were saved', array_keys($messages)));
    }
}