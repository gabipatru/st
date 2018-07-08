<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/AbstractTest.php');
require_once(TRAITS_DIR .'/Filter.trait.php');

/**
 * This class tests the filters
 */
class FilterTest extends AbstractTest
{
    use \Filter;
    
    /**
     * Test boolean filtering function with correct and incorrect data
     * @group fast
     */
    public function testBool()
    {
        // the data
        $var1 = true;
        $var2 = 123;
        $var3 = '123';
        $var4 = [1,2];
        $var5 = false;
        $var6 = 0;
        $var7 = '';
        $var8 = [];
        $var9 = null;
        $var10 = 'false';
        
        // the filtering
        $r1 = $this->filterBool($var1);
        $r2 = $this->filterBool($var2);
        $r3 = $this->filterBool($var3);
        $r4 = $this->filterBool($var4);
        $r5 = $this->filterBool($var5);
        $r6 = $this->filterBool($var6);
        $r7 = $this->filterBool($var7);
        $r8 = $this->filterBool($var8);
        $r9 = $this->filterBool($var9);
        $r10 = $this->filterBool($var10);
        
        // asserts
        $this->assertTrue($r1);
        $this->assertTrue($r2);
        $this->assertTrue($r3);
        $this->assertTrue($r4);
        $this->assertFalse($r5);
        $this->assertFalse($r6);
        $this->assertFalse($r7);
        $this->assertFalse($r8);
        $this->assertFalse($r9);
        $this->assertFalse($r10);
    }
    
    /**
     * Test the integer filtering with correct and incorrect data
     * @group fast
     */
    public function testInt()
    {
        // the data
        $var1 = 123;
        $var2 = '123';
        $var3 = '12ab<>3!()';
        $var4 = null;
        $var5 = '123.45';
        
        // the filtering
        $r1 = $this->filterInt($var1);
        $r2 = $this->filterInt($var2);
        $r3 = $this->filterInt($var3);
        $r4 = $this->filterInt($var4);
        $r5 = $this->filterInt($var5);
        
        // asserts
        $this->assertTrue(123 === $r1);
        $this->assertTrue(123 === $r2);
        $this->assertTrue(123 === $r3);
        $this->assertTrue(0 === $r4);
        $this->assertTrue(12345 === $r5);
    }
    
    /**
     * Test the float filtering with correct and incorrect data
     * @group fast
     */
    public function testFloat()
    {
        // the data
        $var1 = 123.45;
        $var2 = '123.45';
        $var3 = '12ab<>3!.45()';
        $var4 = null;
        
        // the filtering
        $r1 = $this->filterFloat($var1);
        $r2 = $this->filterFloat($var2);
        $r3 = $this->filterFloat($var3);
        $r4 = $this->filterFloat($var4);
        
        // asserts
        $this->assertTrue(123.45 === $r1);
        $this->assertTrue(123.45 === $r2);
        $this->assertTrue(123.45 === $r3);
        $this->assertTrue(0.0 === $r4);
    }
    
    /**
     * Test the string filtering with correct and incorrect data
     * @group fast
     */
    public function testString() {
        // the data
        $var1 = 'str';
        $var2 = 123;
        $var3 = null;
        
        // the filtering
        $r1 = $this->filterString($var1);
        $r2 = $this->filterString($var2);
        $r3 = $this->filterString($var3);
        
        // asserts
        $this->assertEquals('str', $r1);
        $this->assertEquals('123', $r2);
        $this->assertEquals('', $r3);
    }
    
    /**
     * Test the urlencode filtering
     * @group fast
     */
    public function testUrlEncode()
    {
        // the data
        $var1 = 'www.st.ro';
        $var2 = 'www.s t.ro';
        $var3 = 'www.s&t.ro';
        
        // the filtering
        $r1 = $this->filter($var1, 'urlencode');
        $r2 = $this->filter($var2, 'urlencode');
        $r3 = $this->filter($var3, 'urlencode');
        
        // asserts
        $this->assertEquals('www.st.ro', $r1);
        $this->assertEquals('www.s+t.ro', $r2);
        $this->assertEquals('www.s%26t.ro', $r3);
    }
    
    /**
     * Test the urldecode filtering
     * @group fast
     */
    public function testUrlDecode()
    {
        // the data
        $var1 = 'www.st.ro';
        $var2 = 'www.s+t.ro';
        $var3 = 'www.s%26t.ro';
        
        // the filtering
        $r1 = $this->filter($var1, 'urldecode');
        $r2 = $this->filter($var2, 'urldecode');
        $r3 = $this->filter($var3, 'urldecode');
        
        // asserts
        $this->assertEquals('www.st.ro', $r1);
        $this->assertEquals('www.s t.ro', $r2);
        $this->assertEquals('www.s&t.ro', $r3);
    }
    
    /**
     * Test html cleaning
     * @group fast
     */
    public function testCleanHtml()
    {
        // the data
        $var1 = 'html';
        $var2 = '<html"123">';
        
        // the filtering
        $r1 = $this->filter($var1, 'clean_html');
        $r2 = $this->filter($var2, 'clean_html');
        
        // asserts
        $this->assertEquals('html', $r1);
        $this->assertEquals('&lt;html&quot;123&quot;&gt;', $r2);
    }
    
    /**
     * Test the min filtering function with correct and incorrect data
     * @group fast
     */
    public function testMin()
    {
        // the data
        $var1 = 1;
        $var2 = 5;
        $var3 = null;
        
        // the filtering
        $r1 = $this->filterMin($var1, 2);
        $r2 = $this->filterMin($var2, 2);
        $r3 = $this->filterMin($var3, 2);
        
        // asserts
        $this->assertEquals(2, $r1);
        $this->assertEquals(5, $r2);
        $this->assertEquals(2, $r3);
    }
    
    /**
     * Test the date-before filtering function with correct and incorrect data
     * @group fast
     */
    public function testDateBefore()
    {
        // the data
        $var1 = '2018-06-03 10:00:00';
        $var2 = '2018-07-04 11:11:11';
        $var3 = null;
        
        // the filtering
        $r1 = $this->filterBefore($var1, '2018-06-28 00:00:00');
        $r2 = $this->filterBefore($var2, '2018-06-28 00:00:00');
        $r3 = $this->filterBefore($var3, '2018-06-28 00:00:00');
        
        // asserts
        $this->assertEquals('2018-06-28 00:00:00', $r1);
        $this->assertEquals('2018-07-04 11:11:11', $r2);
        $this->assertEquals('2018-06-28 00:00:00', $r3);
    }
    
    /**
     * Test the max filtering function with correct and incorrect data
     * @group fast
     */
    public function testMax()
    {
        // the data
        $var1 = 1;
        $var2 = 5;
        $var3 = null;
        
        // the filtering
        $r1 = $this->filterMax($var1, 2);
        $r2 = $this->filterMax($var2, 2);
        $r3 = $this->filterMax($var3, 2);
        
        // asserts
        $this->assertEquals(1, $r1);
        $this->assertEquals(2, $r2);
        $this->assertEquals(2, $r3);
    }
    
    /**
     * Test the date-after filtering function with correct and incorrect data
     * @group fast
     */
    public function testDateAfter()
    {
        // the data
        $var1 = '2018-06-03 10:00:00';
        $var2 = '2018-07-04 11:11:11';
        $var3 = null;
        
        // the filtering
        $r1 = $this->filterAfter($var1, '2018-06-28 00:00:00');
        $r2 = $this->filterAfter($var2, '2018-06-28 00:00:00');
        $r3 = $this->filterAfter($var3, '2018-06-28 00:00:00');
        
        // asserts
        $this->assertEquals('2018-06-03 10:00:00', $r1);
        $this->assertEquals('2018-06-28 00:00:00', $r2);
        $this->assertEquals('2018-06-28 00:00:00', $r3);
    }
    
    /**
     * Test the interval filtering function with correct and incorrect data
     * @group fast
     */
    public function testInterval() 
    {
        // the data
        $var1 = 1;
        $var2 = 3;
        $var3 = 5;
        $var4 = null;
        
        // the filtering
        $r1 = $this->filterInterval($var1, 2, 4);
        $r2 = $this->filterInterval($var2, 2, 4);
        $r3 = $this->filterInterval($var3, 2, 4);
        $r4 = $this->filterInterval($var4, 2, 4);
        
        // asserts
        $this->assertEquals(2, $r1);
        $this->assertEquals(3, $r2);
        $this->assertEquals(4, $r3);
        $this->assertEquals(2, $r4);
    }
    
    /**
     * Test the date-between filtering function with correct and incorrect data
     * @group fast
     */
    public function testDateBetween()
    {
        // the data
        $var1 = '2018-06-03 10:00:00';
        $var2 = '2018-07-04 11:11:11';
        $var3 = '2018-10-20 11:10:10';
        
        // the filtering
        $r1 = $this->filterDateBetween($var1, '2018-06-28 00:00:00', '2018-07-10 00:00:00');
        $r2 = $this->filterDateBetween($var2, '2018-06-28 00:00:00', '2018-07-10 00:00:00');
        $r3 = $this->filterDateBetween($var3, '2018-06-28 00:00:00', '2018-07-10 00:00:00');
        
        // asserts
        $this->assertEquals('2018-06-28 00:00:00', $r1);
        $this->assertEquals('2018-07-04 11:11:11', $r2);
        $this->assertEquals('2018-07-10 00:00:00', $r3);
    }
    
    /**
     * Test the array filtering function with correct and incorrect data
     * @group fast
     */
    public function testArray()
    {
        // the data
        $array1 = [1, 2];
        $array2 = '123';
        $array3 = null;
        
        // the filtering
        $r1 = $this->filterArray($array1);
        $r2 = $this->filterArray($array2);
        $r3 = $this->filterArray($array3);
        
        // asserts
        $this->assertTrue(is_array($r1));
        $this->assertTrue(is_array($r2));
        $this->assertTrue(is_array($r3));
        $this->assertCount(2, $r1);
        $this->assertCount(0, $r2);
        $this->assertCount(0, $r3);
        $this->assertEquals(1, $r1[0]);
        $this->assertEquals(2, $r1[1]);
    }
    
    /**
     * Test the values set filtering function with correct and incorrect data
     * @group fast
     */
    public function testSerOfValues() {
        // the data
        $set1 = [2, 4, 6];
        $set2 = ['red', 'green', 'blue'];
        
        $var1 = 1;
        $var2 = 2;
        $var3 = 4;
        $var4 = 9;
        $var5 = 'red';
        $var6 = 'yellow';
        
        // the filtering
        $r1 = $this->filterSetOfValues($var1, $set1);
        $r2 = $this->filterSetOfValues($var2, $set1);
        $r3 = $this->filterSetOfValues($var3, $set1);
        $r4 = $this->filterSetOfValues($var4, $set1);
        $r5 = $this->filterSetOfValues($var5, $set2);
        $r6 = $this->filterSetOfValues($var6, $set2);
        
        // asserts
        $this->assertFalse($r1);
        $this->assertEquals(2, $r2);
        $this->assertEquals(4, $r3);
        $this->assertFalse($r4);
        $this->assertEquals('red', $r5);
        $this->assertFalse($r6);
    }
    
    /**
     * Test the email filtering function with correct and incorrect data
     * @group fast
     */
    public function testEmail()
    {
        // the data
        $email1 = 'gabipatru@gmail.com';
        $email2 = 'gabipatru123<>@gmail.com';
        
        // the filtering
        $r1 = $this->filterEmail($email1);
        $r2 = $this->filterEmail($email2);
        
        // asserts
        $this->assertEquals('gabipatru@gmail.com', $r1);
        $this->assertEquals('gabipatru123@gmail.com', $r2);
    }
}