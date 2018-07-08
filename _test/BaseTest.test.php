<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/AbstractTest.php');

/**
 * This class will test basic stuff like folder permissions,
 * file permissions, file existance, etc
 */
class BaseTest extends AbstractTest {
    
    /**
     * Tets if the bash script exist and if they have the correct permissions
     * @group fast
     */
    public function testBashScripts() {
        $allFiles = [
            BASE_DIR .'/install.sh',
            BASE_DIR .'/run_gulp',
            BASE_DIR .'/unit_test.sh'
        ];
        
        foreach ($allFiles as $file) {
            $this->assertTrue(file_exists($file));
            $this->assertEquals('100744', sprintf('%o', fileperms($file)));
        }
    }
    
    /**
     * Test that some folders exist and that they have correct permissions
     * @group fast
     */
    public function testFolders() {
        $allFolders = [
            BASE_DIR,
            CONFIG_DIR,
            CONTROLLER_DIR,
            CLASSES_DIR,
            TRAITS_DIR,
            BASE_DIR .'/_gulp',
            JS_CODE_DIR,
            JS_CODE_DIR .'/js-vendors',
            JS_CODE_DIR .'/js-website',
            BASE_DIR .'/_lib',
            MIGRATIONS_DIR,
            SCRIPT_DIR,
            BASE_DIR .'/_test',
            TRANSLATIONS_DIR,
            VIEW_DIR,
            VIEW_INCLUDES_DIR,
            BASE_DIR .'/_webserver',
            BASE_DIR .'/public_html',
            BASE_DIR .'/public_html/_static',
            BASE_DIR .'/public_html/_static/css',
            BASE_DIR .'/public_html/_static/images',
            BASE_DIR .'/public_html/_static/images/common',
            BASE_DIR .'/public_html/_static/images/website',
            BASE_DIR .'/public_html/_static/js',
        ];
        
        foreach ($allFolders as $folder) {
            $this->assertTrue(is_dir($folder));
            $this->assertEquals('40755', sprintf('%o', fileperms($folder)));
        }
    }
    
    /**
     * Test the folders with all user write permissions
     * @group fast
     */
    public function testUploadFolder() {
        $allFolders = [
            FILES_DIR,
            FILES_DIR .'/log'
        ];
        
        foreach ($allFolders as $folder) {
            $this->assertTrue(is_dir($folder));
            $this->assertEquals('40777', sprintf('%o', fileperms($folder)));
        }
    }
}