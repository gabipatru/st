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
        
        // assert
        $this->assertEquals(count($oConfig), count($oNewConfig));
    }
    
    /**
     * Try adding a config with correct data
     * @group slow
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

        // assert
        $this->assertEquals(count($oConfig) + 1, count($oNewConfig));
    }
}