<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class FileUpload extends AbstractTest
{
    /**
     * Test default settings for file upload
     * @group fast
     */
    public function testDefault()
    {
        $uploader = new \FileUpload();
        
        // asserts
        $this->assertInstanceOf(\FileUpload::class, $uploader);
        $this->assertCount(0, $uploader->getAllowedTypes());
        $this->assertEquals(\FileUpload::DEFAULT_MAX_UPLOAD_SIZE, $uploader->getMaxFileSize());
        $this->assertEquals('php', $uploader->getMimeGetMode());
    }
    
    /**
     * Test allowed file types
     * @group fast
     */
    public function testAllowedFileTypes()
    {
        $uploader = new \FileUpload();
        $uploader->addAllowedType('image/png');
        
        $this->assertCount(1, $uploader->getAllowedTypes());
        $this->assertTrue(in_array('image/png', $uploader->getAllowedTypes()));
        
        $uploader->addAllowedType('image/gif');
        
        $this->assertCount(2, $uploader->getAllowedTypes());
        $this->assertTrue(in_array('image/png', $uploader->getAllowedTypes()));
        $this->assertTrue(in_array('image/gif', $uploader->getAllowedTypes()));
    }
    
    /**
     * Test checkMimeType
     * @group fast
     */
    public function testCheckMimeType()
    {
        $uploader = new \FileUpload();
        $uploader->addAllowedType('image/png');
        $uploader->addAllowedType('image/gif');
        
        // asserts
        $this->assertTrue($uploader->checkMimeType('image/gif'));
        $this->assertFalse($uploader->checkMimeType('image/jpeg'));
    }
}