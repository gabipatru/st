<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class SetterGetter extends AbstractTest {
    
    /**
     * @group slow
     */
    public function testSetterGetter() {
        $Var = new \SetterGetter();
        
        $Var->setVar(5);
        
        // asserts
        $this->assertTrue($Var instanceof \SetterGetter);
        $this->assertFalse($Var instanceof SetterGetter);
        
        $this->assertEquals(5, $Var->getVar());
        $this->assertNotEquals(6, $Var->getVar());
        
        $this->assertNull($Var->getValue());
    }
}