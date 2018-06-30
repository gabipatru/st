<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class EmailTemplate extends AbstractTest {
    
    /**
     * Test setting view file and decorations
     * @group fast
     */
    public function testSetters() {
        $Email = new \EmailTemplate('test');
        
        // assert data and files and folders exist
        $this->assertEquals('test', $Email->getViewFile());
        $this->assertEquals('_default', $Email->getDecorations());
        
        $this->assertTrue(is_dir(EMAIL_VIEW_DIR. '/_core'));
        $this->assertTrue(file_exists(EMAIL_VIEW_DIR. '/_core/header.php'));
        $this->assertTrue(file_exists(EMAIL_VIEW_DIR. '/_core/footer.php'));
        
        $this->assertTrue(is_dir(EMAIL_DECORATIONS_DIR. '/' .$Email->getDecorations()));
        $this->assertTrue(file_exists(EMAIL_DECORATIONS_DIR .'/'. $Email->getDecorations() .'/header.php'));
        $this->assertTrue(file_exists(EMAIL_DECORATIONS_DIR .'/'. $Email->getDecorations() .'/footer.php'));
        
        $Email->setViewFile('testx');
        $Email->setDecorations('testy');
        
        // assert new data is correct
        $this->assertEquals('testx', $Email->getViewFile());
        $this->assertEquals('testy', $Email->getDecorations());
    }
}