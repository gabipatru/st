<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../../AbstractControllerTest.php');

class controller_admin_cache extends AbstractControllerTest
{
    /**
     * Test if memcache is cleared when token is wrong
     * @group slow
     */
    public function test_flush_all_memcahed_wrong_token()
    {
        $this->setUpDB([ 'config' ]);
        
        $oMockController = $this->initController('/admin/index.php/admin/cache/flush_all_memcache');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);
        
        // make sure the config memcache key exists
        $oConfigModel = new \Config();
        $config = $oConfigModel->Get();
        
        $Memcache = \Mcache::getSingleton();
        
        // check if the config key exists
        $config = $Memcache->get(\Config::MEMCACHE_KEY);
        
        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(100, strlen($config));
        
        // run the test
        $oMockController->flush_all_memcached();
        
        // check if the key is deleted
        $config = $Memcache->get(\Config::MEMCACHE_KEY);
        
        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(100, strlen($config));
    }
    
    /**
     * Test if memcache is cleared when it's not a post
     * @group slow
     * @depends test_flush_all_memcahed_wrong_token
     */
    public function test_flush_all_memcahed_not_post()
    {
        $oMockController = $this->initController('/admin/index.php/admin/cache/flush_all_memcache');
        $this->mockIsPost(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        
        // make sure the config memcache key exists
        $oConfigModel = new \Config();
        $config = $oConfigModel->Get();
        
        $Memcache = \Mcache::getSingleton();
        
        // check if the config key exists
        $config = $Memcache->get(\Config::MEMCACHE_KEY);
        
        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(100, strlen($config));
        
        // run the test
        $oMockController->flush_all_memcached();
        
        // check if the key is deleted
        $config = $Memcache->get(\Config::MEMCACHE_KEY);
        
        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(100, strlen($config));
    }
    
    /**
     * Test flushing of all memcache keys
     * @group slow
     * @depends test_flush_all_memcahed_wrong_token
     */
    public function test_flush_all_memcached()
    {
        $oMockController = $this->initController('/admin/index.php/admin/cache/flush_all_memcache');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        
        // make sure the config memcache key exists
        $oConfigModel = new \Config();
        $config = $oConfigModel->Get();
        
        $Memcache = \Mcache::getSingleton();
        
        // check if the config key exists
        $config = $Memcache->get(\Config::MEMCACHE_KEY);
        
        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(1, strlen($config));
        
        // run the test
        $oMockController->flush_all_memcached();
        
        // check if the key is deleted
        $config = $Memcache->get(\Config::MEMCACHE_KEY);
        
        $this->assertInternalType(IsType::TYPE_BOOL, $config);
        $this->assertFalse($config);
    }

    /**
     * Test if memcache is cleared when the token is wrong
     * @group slow
     * @depends test_flush_all_memcahed_wrong_token
     */
    public function test_flush_memcached_not_post()
    {
        $oMockController = $this->initController('/admin/index.php/admin/cache/flush_memcache');
        $this->mockIsPost(false, $oMockController);
        $oMockController->expects($this->never())->method('validate');

        // make sure the config memcache key exists
        $oConfigModel = new \Config();
        $config = $oConfigModel->Get();

        $Memcache = \Mcache::getSingleton();

        // check if the config key exists
        $config = $Memcache->get(\Config::MEMCACHE_KEY);

        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(100, strlen($config));

        // run the test
        $oMockController->flush_memcached();

        // check if the key is deleted
        $config = $Memcache->get(\Config::MEMCACHE_KEY);

        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(100, strlen($config));
    }

    /**
     * Test what happens when the validation fails for flush_memcached
     * @group slow
     * @depends test_flush_all_memcahed_wrong_token
     */
    public function test_flush_memcached_validations_fails()
    {
        // init
        $oMockController = $this->initController('/admin/index.php/admin/cache/flush_memcache');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(false, $oMockController);
        $oMockController->expects($this->never())->method('securityCheckToken');

        // run the test
        $oMockController->flush_memcached();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Please make sure you filled all the required fields', array_keys($messages)));
    }

    /**
     * Test what happens when the token is invalid for flush_memcached
     * @group slow
     * @depends test_flush_all_memcahed_wrong_token
     */
    public function test_flush_memcached_invalid_token()
    {
        // init
        $oMockController = $this->initController('/admin/index.php/admin/cache/flush_memcache');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);

        // run the test
        $oMockController->flush_memcached();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test flushing of a single memcache key
     * @group slow
     * @depends test_flush_all_memcahed_wrong_token
     */
    public function test_flush_memcached()
    {
        // init
        $oMockController = $this->initController('/admin/index.php/admin/cache/flush_memcache');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setPOST(['memcached_key' => \Config::MEMCACHE_KEY], $oMockController);

        // make sure the config memcache key exists
        $oConfigModel = new \Config();
        $config = $oConfigModel->Get();

        $Memcache = \Mcache::getSingleton();

        // check if the config key exists
        $config = $Memcache->get(\Config::MEMCACHE_KEY);

        $this->assertInternalType(IsType::TYPE_STRING, $config);
        $this->assertGreaterThan(1, strlen($config));

        // run the test
        $oMockController->flush_memcached();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);

        // check if the key is deleted
        $config = $Memcache->get(\Config::MEMCACHE_KEY);

        $this->assertInternalType(IsType::TYPE_BOOL, $config);
        $this->assertFalse($config);
    }
}