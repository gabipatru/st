<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../AbstractTest.php');

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
     * Test if the file extension is properly fetched
     * @group fast
     */
    public function testGetFullFileExtension()
    {
        $uploader = new \FileUpload();
        $uploader->setFileExtension('jpg');
        
        // asserts
        $this->assertEquals('jpg', $uploader->getFileExtension());
        $this->assertEquals('.jpg', $uploader->getFullFileExtension());
    }
    
    /**
     * Test if the extension can be exxtracted from the file
     * @group fast
     * @dataProvider providerGetExtension
     */
    public function testGetExtension($file, $expectedExtension)
    {
        $uploader = new \FileUpload();
        $uploader->setSourceFileName($file);
        
        $extension = $uploader->getExtension();
        
        $this->assertEquals($expectedExtension, $extension);
    }
    
    public function providerGetExtension()
    {
        $path = BASE_DIR . '/_test/resource/testfiles';
        
        return [
            [$path . '/4933.mp3', 'mp3'],
            [$path . '/file.txt', 'txt'],
            [$path . '/file.html', 'html'],
            [$path . '/file.php', 'php']
        ];
    }
    
    /**
     * Test fetching of the source files
     * @group fast
     * @dataProvider providerGetSourceFile
     */
    public function testGetSourceFile($field, $fileName, $expected)
    {
        $uploader = new \FileUpload();
        $uploader->setFieldName($field);
        $uploader->setSourceFileName($fileName);
        
        $soourceFile = $uploader->getSourceFile();
        
        $this->assertEquals($expected, $soourceFile);
    }
    
    public function providerGetSourceFile()
    {
        return [
            ['test', null, false],
            [null, 'file.txt', 'file.txt'],
            ['test', 'file.txt', false]
        ];
    }
    
    /**
     * Test the fileExists functionality
     * @group fast
     * @dataProvider providerFileExists
     */
    public function testFileExists($file, $expected)
    {
        $uploader = new \FileUpload();
        $uploader->setSourceFileName($file);
        
        $result = $uploader->fileExists();
        
        $this->assertEquals($expected, $result);
    }
    
    public function providerFileExists()
    {
        $path = BASE_DIR . '/_test/resource/testfiles';
        
        return [
            [$path . '/4933.mp3', true],
            [$path . '/file.txt', true],
            [$path . '/file.html', true],
            [$path . '/file.php', true],
            [$path . '/file.jpg', true],
            [$path . '/file.png', true],
            [$path . '/file.gif', true],
            [$path . '/file.ext', false],
            [$path . '/test.jpg', false]
        ];
    }
    
    /**
     * Test getDestinationFile functionality
     * @group fast
     */
    public function testGetDestinationFile()
    {
        $uploader = new \FileUpload();
        $uploader->setFileName('test');
        $uploader->setFileExtension('jpg');
        
        $destinationFile = $uploader->getDestinationFile();
        
        $this->assertEquals('test.jpg', $destinationFile);
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
    
    /**
     * Test if the correct mime type is found
     * @group fast
     * @dataProvider providerGetMimeType
     */
    public function testGetMimeType($file, $mime, $mode)
    {
        $uploader = new \FileUpload();
        $uploader->setSourceFileName($file);
        
        $this->assertEquals($mime, $uploader->getMimeType($mode));
    }
    
    public function providerGetMimeType()
    {
        return [
            [self::RESOURCE_PATH . '/4933.mp3', 'audio/mpeg', 'internal'],
            [self::RESOURCE_PATH . '/file.txt', 'text/plain', 'php'],
            [self::RESOURCE_PATH . '/file.html', 'text/html', 'php'],
            [self::RESOURCE_PATH . '/file.php', 'text/x-php', 'php'],
            [self::RESOURCE_PATH . '/4933.mp3', 'audio/mpeg', 'php'],
            [self::RESOURCE_PATH . '/file.gif', 'image/gif', 'php'],
            [self::RESOURCE_PATH . '/file.png', 'image/png', 'php'],
            [self::RESOURCE_PATH . '/file.jpg', 'image/jpeg', 'php'],
            [self::RESOURCE_PATH . '/file.txt', 'text/plain', 'linux-file'],
            [self::RESOURCE_PATH . '/file.html', 'text/html', 'linux-file'],
            [self::RESOURCE_PATH . '/file.php', 'text/x-php', 'linux-file'],
            [self::RESOURCE_PATH . '/4933.mp3', 'audio/mpeg', 'linux-file'],
            [self::RESOURCE_PATH . '/file.gif', 'image/gif', 'linux-file'],
            [self::RESOURCE_PATH . '/file.png', 'image/png', 'linux-file'],
            [self::RESOURCE_PATH . '/file.jpg', 'image/jpeg', 'linux-file']
        ];
    }
}
