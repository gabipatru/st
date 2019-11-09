<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../AbstractTest.php');

/**
 * Test the mvc class
 */
class Mvc extends AbstractTest
{
    /**
     * Test singleton and controllerFunction
     * @group fast
     */
    public function testSingleton()
    {
        $mvc = \mvc::getSingleton();
        $mvc->setControllerFunction('test');
        
        $mvc2 = \mvc::getSingleton();
        $mvc2->setControllerFunction('cest');
        
        // asserts
        $this->assertSame($mvc, $mvc2);
        $this->assertEquals('cest', $mvc->getControllerFunction());
        $this->assertEquals('cest', $mvc2->getControllerFunction());
    }
    
    /**
     * Test if the controller class is set correctly
     * @group fast
     */
    public function testControllerClass()
    {
        $mvc = \mvc::getSingleton();
        $mvc->setControllerClass('class');
        
        // assert
        $this->assertEquals('controller_class', $mvc->getControllerClass());
    }
    
    /**
     * Test if the controller file is set correctly
     * @group fast
     */
    public function testControllerFile()
    {
        $mvc = \mvc::getSingleton();
        $mvc->setControllerFile('admin');
        
        // assert
        $this->assertEquals(CONTROLLER_DIR . '/admin.php', $mvc->getControllerFile());
    }
    
    public function providerExtract()
    {
        return [
            ['', ['website', 'website', 'homepage']],
            ['/user/index.php/user/login', ['user', 'user', 'login']],
            ['/admin/index.php/admin/categories/list_categories', ['admin_categories', 'admin/categories', 'list_categories']],
            ['/user/index.php/../../user/login', ['user', 'user', 'login']],
        ];
    }
    
    /**
     * Test extract function
     * @dataProvider providerExtract
     * @group fast
     */
    public function testExtract($input, $aTest)
    {
        $mvcMock = $this->getMockBuilder('\mvc')
                        ->disableOriginalConstructor()
                        ->setMethods(['extract', 'serverSelf'])
                        ->getMock();
        
        $ref = new \ReflectionProperty('\mvc', 'instance');
        $ref->setAccessible(true);
        $ref->setValue(null, $mvcMock);
        
        $mvcMock->expects($this->once())->method('serverSelf')->willReturn($input);
        
        // the test
        list($sClass, $sPath, $sFunction) = $this->invokeMethod($mvcMock, 'extract');
        
        // asserts
        $this->assertEquals($aTest[0], $sClass);
        $this->assertEquals($aTest[1], $sPath);
        $this->assertEquals($aTest[2], $sFunction);
    }
}
