<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class ImageUpload extends AbstractTest
{
    /**
     * Test if ImageUpload class is initialised correctly
     * @group fast
     */
    public function testImageUploadInit()
    {
        $uploader = new \ImageUpload();
        
        $arrAllowedTypes = $uploader->getAllowedTypes();
        
        // asserts
        $this->assertCount(3, $arrAllowedTypes);
        $this->assertTrue(in_array('image/jpeg', $arrAllowedTypes));
        $this->assertTrue(in_array('image/png', $arrAllowedTypes));
        $this->assertTrue(in_array('image/gif', $arrAllowedTypes));
    }
    
    /**
     * Test if PHP GD is installed and the functions are available
     * @group fast
     */
    public function testPHPGD()
    {
        $this->assertTrue(function_exists('imagecreatefromjpeg'));
        $this->assertTrue(function_exists('imagecreatetruecolor'));
        $this->assertTrue(function_exists('imagecopyresampled'));
    }
    
    /**
     * Tets if the height is correctly calculated to keep aspect ratio of the image
     * @group fast
     * @dataProvider providerKeepAspectRatio
     */
    public function testKeepAspectRatio($width, $height, $newWidth, $newHeight, $expectedWidth, $expectedHeight)
    {
        $uploader = new \ImageUpload();
        
        list($newW, $newH) = $this->invokeMethod($uploader, 'keepAspectRatio', [$newWidth, $newHeight, $width, $height]);
        
        $this->assertEquals($expectedWidth, $newW);
        $this->assertEquals($expectedHeight, $newH);
    }
    
    public function providerKeepAspectRatio()
    {
        return[
            [16, 16, 32, 32, 32, 32],
            [16, 16, 32, 16, 32, 32],
            [16, 16, 8, 16, 8, 8],
            [16, 16, 24, 15, 24, 24],
            [16, 16, 12, 15, 12, 12]
        ];
    }
    
    /**
     * Test the JPG resize without crop
     * @group fast
     * @dataProvider providerJPGResize
     */
    public function testJPGResize($width, $height, $expectedWidth, $expectedHeight, $stretch)
    {
        $uploader = new \ImageUpload();
        
        $uploader->setSourceFileName(BASE_DIR .'/_test/resource/testfiles/file.jpg');
        $uploader->setUploadPath(BASE_DIR .'/_test/resource/testfiles');
        $uploader->setFileName('file_resized');
        $uploader->ResizeTo($width, $height);
        $uploader->setStretch($stretch);
        
        $result = $this->invokeMethod($uploader, 'ResizeJPG');
        
        // asserts
        $this->assertTrue($result);
        $this->assertTrue( file_exists(BASE_DIR .'/_test/resource/testfiles/file_resized' .'.jpg') );
        
        // check image size
        list( $width, $height ) = getimagesize(BASE_DIR .'/_test/resource/testfiles/file_resized' .'.jpg');
        $this->assertEquals($expectedWidth, $width);
        $this->assertEquals($expectedHeight, $height);
        
        unlink(BASE_DIR .'/_test/resource/testfiles/file_resized' .'.jpg');
    }
    
    public function providerJPGResize()
    {
        return [
            [32, 32, 32, 32, false],
            [32, 24, 32, 32, false],
            [12, 24, 12, 12, false],
            [32, 24, 32, 24, true],
            [12, 24, 12, 24, true]
        ];
    }
    
    /**
     * Test the PNG resize without crop
     * @group fast
     * @dataProvider providerPNGResize
     */
    public function testPNGResize($width, $height, $expectedWidth, $expectedHeight, $stretch)
    {
        $uploader = new \ImageUpload();
        
        $uploader->setSourceFileName(BASE_DIR .'/_test/resource/testfiles/file.png');
        $uploader->setUploadPath(BASE_DIR .'/_test/resource/testfiles');
        $uploader->setFileName('file_resized');
        $uploader->ResizeTo($width, $height);
        $uploader->setStretch($stretch);
        
        $result = $this->invokeMethod($uploader, 'ResizePNG');
        
        // asserts
        $this->assertTrue($result);
        $this->assertTrue( file_exists(BASE_DIR .'/_test/resource/testfiles/file_resized' .'.png') );
        
        // check image size
        list( $width, $height ) = getimagesize(BASE_DIR .'/_test/resource/testfiles/file_resized' .'.png');
        $this->assertEquals($expectedWidth, $width);
        $this->assertEquals($expectedHeight, $height);
        
        unlink(BASE_DIR .'/_test/resource/testfiles/file_resized' .'.png');
    }
    
    public function providerPNGResize()
    {
        return [
            [48, 48, 48, 48, false],
            [32, 24, 32, 32, false],
            [12, 24, 12, 12, false],
            [32, 24, 32, 24, true],
            [12, 24, 12, 24, true]
        ];
    }
}