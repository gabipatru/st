<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class View extends AbstractTest
{
    /**
     * Test Singleton
     * @group fast
     */
    public function testSingleton()
    {
        $View1 = \View::getSingleton();
        $View1->setTest('test');
        
        $View2 = \View::getSingleton();
        
        // asserts
        $this->assertSame($View1, $View2);
        $this->assertEquals('test', $View1->getTest());
        $this->assertEquals('test', $View2->getTest());
    }
    
    /**
     * Test files for default decorations
     * @group fast
     */
    public function testFiles()
    {
        $View = \View::getSingleton();
        
        $View->setViewFile(VIEW_DIR .'/website/homepage.php');
        $View->setViewDir('website');
        $View->setDecorations('website');
        
        $this->assertTrue(file_exists(VIEW_DIR.'/_core/header.php'));
        $this->assertTrue(file_exists(DECORATIONS_DIR .'/'. $View->getDecorations() .'/header.php'));
        $this->assertTrue(file_exists($View->getViewFile()));
        $this->assertTrue(file_exists(DECORATIONS_DIR .'/'. $View->getDecorations() .'/footer.php'));
        $this->assertTrue(file_exists(VIEW_DIR.'/_core/footer.php'));
    }
}