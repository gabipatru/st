<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/AbstractTest.php');
require_once(TRAITS_DIR .'/Html.trait.php');

/**
 * This class tests the methods in Html trait
 */
class HtmlTest extends AbstractTest
{
    use \Html;
    
    /**
     * Test if the selected property is displayed correctly
     * @group fast
     */
    public function testSelectedIsSelected()
    {
        $this->expectOutputString('selected="SELECTED"');
        
        $this->selected('test', 'test');
    }
    
    /**
     * Test if the selected property is not displayed
     * @group fast
     */
    public function testSelectedIsNotSelected()
    {
        $this->expectOutputString('');
        
        $this->selected('test', 'test1');
    }
    
    /**
     * Test the function that converts a number to KB, MB with bytes
     * @group fast
     */
    public function testDisplayBytesWithBytes()
    {
        $value1 = $this->displayBytes(12);
        $value2 = $this->displayBytes(123);
        $value3 = $this->displayBytes(1001);
        
        $this->assertEquals('12 B', $value1);
        $this->assertEquals('123 B', $value2);
        $this->assertEquals('1001 B', $value3);
    }
    
    /**
     * Test the function that converts a number to KB, MB with KB
     * @group fast
     */
    public function testDisplayBytesWithKB()
    {
        $value1 = $this->displayBytes(1025);
        $value2 = $this->displayBytes(1234);
        $value3 = $this->displayBytes(11782);
        $value4 = $this->displayBytes(324654);
        $value5 = $this->displayBytes(1040123);
        
        $this->assertEquals('      1.00 KB', $value1);
        $this->assertEquals('      1.21 KB', $value2);
        $this->assertEquals('     11.51 KB', $value3);
        $this->assertEquals('    317.04 KB', $value4);
        $this->assertEquals('   1015.75 KB', $value5);
    }
    
    /**
     * Test the function that converts a number to KB, MB with MB
     * @group fast
     */
    public function testDisplayBytesWithMB()
    {
        $value1 = $this->displayBytes(1049123);
        $value2 = $this->displayBytes(1234987);
        $value3 = $this->displayBytes(11765123);
        $value4 = $this->displayBytes(765234876);
        $value5 = $this->displayBytes(1023541265);
        
        $this->assertEquals('      1.00 MB', $value1);
        $this->assertEquals('      1.18 MB', $value2);
        $this->assertEquals('     11.22 MB', $value3);
        $this->assertEquals('    729.78 MB', $value4);
        $this->assertEquals('    976.12 MB', $value5);
    }
}