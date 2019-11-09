<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../AbstractTest.php');

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
        
        $View->setViewFile(VIEW_DIR . '/website/homepage.php');
        $View->setViewDir('website');
        $View->setDecorations('website');
        
        $this->assertTrue(file_exists(VIEW_DIR . '/_core/header.php'));
        $this->assertTrue(file_exists(DECORATIONS_DIR . '/' . $View->getDecorations() . '/header.php'));
        $this->assertTrue(file_exists($View->getViewFile()));
        $this->assertTrue(file_exists(DECORATIONS_DIR . '/' . $View->getDecorations() . '/footer.php'));
        $this->assertTrue(file_exists(VIEW_DIR . '/_core/footer.php'));
    }
    
    /**
     * Test cache busters
     * @group fast
     */
    public function testCacheBusters()
    {
        $View = \View::getSingleton();
        
        $data = ['key1' => 1, 'key2' => 2];
        $View->addCacheBuster($data);
        
        $newData = $View->getCacheBuster();
        
        // asserts
        $this->assertSame($data, $newData);
    }
    
    /**
     * Test add CSS
     * @group fast
     */
    public function testAddCss()
    {
        $View = \View::getSingleton();
        
        $View->addCSS('/home/website/style.css');
        $View->addCSS('/admin/users/layout.css');
        
        $aCSS = $View->getCSS();
        
        // asserts
        $this->assertCount(2, $aCSS);
        $this->assertEquals($aCSS[0], '/home/website/style.css');
        $this->assertEquals($aCSS[1], '/admin/users/layout.css');
    }
    
    /**
     * Test add JS
     * @group fast
     */
    public function testAddJS()
    {
        $View = \View::getSingleton();
        
        $View->addJS('/home/website/style.js');
        $View->addJS('/admin/users/layout.js');
        
        $aJS = $View->getJS();
        
        // asserts
        $this->assertCount(2, $aJS);
        $this->assertEquals($aJS[0], '/home/website/style.js');
        $this->assertEquals($aJS[1], '/admin/users/layout.js');
    }
    
    /**
     * Test add meta
     * @group fast
     */
    public function testAddMeta()
    {
        $View = \View::getSingleton();
        
        $View->addMeta('test', 'meta');
        $View->addMeta('content', 'beta');
        
        $meta = $View->getMeta();
        
        // asserts
        $this->assertCount(2, $meta);
        $this->assertTrue(array_key_exists('test', $meta));
        $this->assertTrue(in_array('meta', $meta));
        $this->assertTrue(array_key_exists('content', $meta));
        $this->assertTrue(in_array('beta', $meta));
    }
    
    /**
     * Test normal assign and assign-escape
     * @group fast
     */
    public function testAssign()
    {
        $View = \View::getSingleton();
        
        $arr = ['assign1' => true, 'assign2' => false];
        
        // assign
        $View->assign('test', 'test1');
        $View->assign('array', $arr);
        $View->assignEscape('escaped', 'test"test');
        
        // asserts
        $this->assertSame('test1', $View->getAssignedVar('test'));
        $this->assertSame($arr, $View->getAssignedVar('array'));
        $this->assertEquals('test&quot;test', $View->getAssignedVar('escaped'));
    }
    
    /**
     * Test recursive assign-escape
     * @group fast
     */
    public function testAssignEscape()
    {
        $View = \View::getSingleton();
        
        $arr = [
            'key' => ['test"test', 'test>', '<test']
        ];
        
        $View->assignEscape('arr', $arr);
        $newArr = $View->getAssignedVar('arr');
        
        // asserts
        $this->assertCount(1, $newArr);
        $this->assertEquals('test&quot;test', $newArr['key'][0]);
        $this->assertEquals('test&gt;', $newArr['key'][1]);
        $this->assertEquals('&lt;test', $newArr['key'][2]);
    }
    
    /**
     * Test if some important files or folders exist
     * @group fast
     */
    public function testViewFilesAndFolders()
    {
        // check if the main header and footer exist
        $this->assertTrue(file_exists(VIEW_DIR . '/_core/header.php'));
        $this->assertEquals('100644', sprintf('%o', fileperms(VIEW_DIR . '/_core/header.php')));
        $this->assertTrue(file_exists(VIEW_DIR . '/_core/header_css.php'));
        $this->assertEquals('100644', sprintf('%o', fileperms(VIEW_DIR . '/_core/header_css.php')));
        $this->assertTrue(file_exists(VIEW_DIR . '/_core/header_js.php'));
        $this->assertEquals('100644', sprintf('%o', fileperms(VIEW_DIR . '/_core/header_js.php')));
        $this->assertTrue(file_exists(VIEW_DIR . '/_core/header_meta.php'));
        $this->assertEquals('100644', sprintf('%o', fileperms(VIEW_DIR . '/_core/header_meta.php')));
        $this->assertTrue(file_exists(VIEW_DIR . '/_core/footer.php'));
        $this->assertEquals('100644', sprintf('%o', fileperms(VIEW_DIR . '/_core/footer.php')));
        
        // check some folders
        $this->assertTrue(is_dir(VIEW_DIR . '/_core/decorations'));
        $this->assertEquals('40755', sprintf('%o', fileperms(VIEW_DIR . '/_core/decorations')));
    }
}
