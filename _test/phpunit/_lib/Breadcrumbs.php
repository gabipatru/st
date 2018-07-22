<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class Breadcrumbs extends AbstractTest {
    
    /**
     * Basic test for breadcrumbs
     * @group fast
     */
    public function testBasic() {
        $br1 = \Breadcrumbs::getSingleton();
        
        $this->assertInstanceOf(\Breadcrumbs::class, $br1);
        $this->assertTrue(is_array($br1->getBreadcrumbs));
        $this->assertEmpty($br1->getBreadcrumbs());
    }
    
    /**
     * Test if the breadcrumbs function is a singleton
     * @group fast
     */
    public function testSingleton() {
        $br1 = \Breadcrumbs::getSingleton();
        $br1->Add('test', 'test');
        
        $this->assertCount(1, $br1->getBreadcrumbs());
        
        $br2 = \Breadcrumbs::getSingleton();
        $br2->Add('test2', 'test2');
        
        $this->assertCount(2, $br2->getBreadcrumbs());
        $this->assertCount(2, $br2->getBreadcrumbs());
    }
}