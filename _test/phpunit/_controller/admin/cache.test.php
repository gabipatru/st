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
     * Test if memcache is cleared it's not a post
     * @group slow
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
}