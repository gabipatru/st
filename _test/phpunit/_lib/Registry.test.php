<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error\Warning;

require_once(__DIR__ . '/../AbstractTest.php');

class Registry extends AbstractTest
{
    /**
     * Test Registry in action
     * @group fast
     */
    public function testRegistry()
    {
        $reg1 = \Registry::getSingleton();
        $reg1->set('key1', 'abc');
        
        $this->assertEquals('abc', $reg1->get('key1'));
    }
    
    /**
     * Test warning on overwriting key
     * @group fast
     */
    public function testRegistryOverride()
    {
        $this->expectException(Warning::class);
        
        $reg1 = \Registry::getSingleton();
        $reg1->set('key1', 'abc');
        $reg1->set('key1', 'def');
    }
    
    /**
     * tets overwriting key with no warning
     * @group fast
     */
    public function testRegistryOverridingNoWarning()
    {
        $reg1 = \Registry::getSingleton();
        $reg1->setShowWarning(false);
        
        $reg1->set('key1', 'abc');
        $reg1->set('key1', 'def');
        
        $this->assertTrue(true);
    }
    
    /**
     * Test Registry singleton
     * @group fast
     */
    public function testRegistrySingleton()
    {
        $this->expectException(Warning::class);
        
        $reg1 = \Registry::getSingleton();
        $reg1->setShowWarning(true);
        
        $reg1->set('key1', 'abc');
        
        $this->assertEquals('abc', $reg1->get('key1'));
        
        $reg2 = \Registry::getSingleton();
        $reg2->set('key1', 'def');
        
        $this->assertEquals('def', $reg2->get('key1'));
        $this->assertEquals('def', $reg1->get('key1'));
    }
}
