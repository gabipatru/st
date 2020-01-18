<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ . '/../AbstractTest.php');

class SetterGetter extends AbstractTest
{
    /**
     * Basic test for SetterGetter
     * @group fast
     */
    public function testSetterGetter()
    {
        $Var = new \SetterGetter();
        
        $Var->setVar(5);
        
        // asserts
        $this->assertTrue($Var instanceof \SetterGetter);
        $this->assertFalse($Var instanceof SetterGetter);
        
        $this->assertEquals(5, $Var->getVar());
        $this->assertNotEquals(6, $Var->getVar());
        
        $this->assertNull($Var->getValue());
    }

    /**
     * Test the function allFieldsByArray with wrong parameter
     * @group fast
     */
    public function testAllFieldsByArrayWrongData()
    {
        $oItem = new \SetterGetter();

        // the test
        $result = $oItem->allFieldsByArray('test');

        // assert
        $this->assertFalse($result);
    }

    /**
     * Test the function allFieldsByArray with correct data
     * @group fast
     */
    public function testAllFieldsByArray()
    {
        // init
        $oItem = new \SetterGetter();
        $oItem->setCategoryId(1);
        $oItem->setName('the name');
        $oItem->setShortDescription('Some description');
        $oItem->setStatus('online');

        // the test
        $result = $oItem->allFieldsByArray(['name', 'short_description']);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertEquals('the name', $result['name']);
        $this->assertArrayHasKey('short_description', $result);
        $this->assertEquals('Some description', $result['short_description']);
        $this->assertFalse(array_key_exists('status', $result));
    }
}
