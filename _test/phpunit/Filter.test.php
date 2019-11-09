<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/AbstractTest.php');
require_once(TRAITS_DIR . '/Filter.trait.php');

/**
 * This class tests the filters
 */
class FilterTest extends AbstractTest
{
    use \Filter;
    
    /**
     * Test boolean filtering function with correct and incorrect data
     * @group fast
     * @dataProvider providerBool
     */
    public function testBool($var, $expected)
    {
        // the filtering
        $result = $this->filterBool($var);
        
        // asserts
        $this->assertTrue($expected === $result);
    }
    
    public function providerBool()
    {
        return [
            [true,      true],
            [123,       true],
            ['123',     true],
            [[1, 2],    true],
            [false,     false],
            [0,         false],
            ['',        false],
            [null,      false],
            ['false',   false]
        ];
    }
    
    /**
     * Test the integer filtering with correct and incorrect data
     * @group fast
     * @dataProvider providerInt
     */
    public function testInt($var, $expected)
    {
        // the filtering
        $result = $this->filterInt($var);
        
        // asserts
        $this->assertTrue($expected === $result);
    }
    
    public function providerInt()
    {
        return [
            [123,           123],
            ['123',         123],
            ['12ab<>3!()',  123],
            [null,          0],
            ['123.45',      12345]
        ];
    }
    
    /**
     * Test the float filtering with correct and incorrect data
     * @group fast
     * @dataProvider providerFloat
     */
    public function testFloat($var, $expeccted)
    {
        // the filtering
        $result = $this->filterFloat($var);
        
        // asserts
        $this->assertTrue($result === $expeccted);
    }
    
    public function providerFloat()
    {
        return [
            [123.45,            123.45],
            ['123.45',          123.45],
            ['12ab<>3!.45()',   123.45],
            [null,              0.0]
        ];
    }
    
    /**
     * Test the string filtering with correct and incorrect data
     * @group fast
     * @dataProvider providerString
     */
    public function testString($var, $expected)
    {
        // the filtering
        $result = $this->filterString($var);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerString()
    {
        return [
            ['str', 'str'],
            [123,   '123'],
            [null,  '']
        ];
    }
    
    /**
     * Test the urlencode filtering
     * @group fast
     * @dataProvider providerUrlEncode
     */
    public function testUrlEncode($var, $expected)
    {
        // the filtering
        $result = $this->filter($var, 'urlencode');
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerUrlEncode()
    {
        return [
            ['www.st.ro',   'www.st.ro'],
            ['www.s t.ro',  'www.s+t.ro'],
            ['www.s&t.ro',  'www.s%26t.ro']
        ];
    }
    
    /**
     * Test the urldecode filtering
     * @group fast
     * @dataProvider providerUrlDecode
     */
    public function testUrlDecode($var, $expected)
    {
        // the filtering
        $result = $this->filter($var, 'urldecode');
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerUrlDecode()
    {
        return [
            ['www.st.ro',       'www.st.ro'],
            ['www.s+t.ro',      'www.s t.ro'],
            ['www.s%26t.ro',    'www.s&t.ro']
        ];
    }
    
    /**
     * Test html cleaning
     * @group fast
     * @dataProvider providerCleanHtml
     */
    public function testCleanHtml($var, $expected)
    {
        // the filtering
        $result = $this->filter($var, 'clean_html');
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerCleanHtml()
    {
        return [
            ['html',        'html'],
            ['<html"123">', '&lt;html&quot;123&quot;&gt;']
        ];
    }
    
    /**
     * Test the min filtering function with correct and incorrect data
     * @group fast
     * @dataProvider providerMin
     */
    public function testMin($var, $value, $expected)
    {
        // the filtering
        $result = $this->filterMin($var, 2);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerMin()
    {
        return [
            [1,     2, 2],
            [5,     2, 5],
            [null,  2, 2]
        ];
    }
    
    /**
     * Test the date-before filtering function with correct and incorrect data
     * @group fast
     * @dataProvider providerDateBefore
     */
    public function testDateBefore($var, $value, $expected)
    {
        // the filtering
        $resule = $this->filterBefore($var, $value);
        
        // asserts
        $this->assertEquals($expected, $resule);
    }
    
    public function providerDateBefore()
    {
        return [
            ['2018-06-03 10:00:00', '2018-06-28 00:00:00', '2018-06-28 00:00:00'],
            ['2018-07-04 11:11:11', '2018-06-28 00:00:00', '2018-07-04 11:11:11'],
            [null,                  '2018-06-28 00:00:00', '2018-06-28 00:00:00']
        ];
    }
    
    /**
     * Test the max filtering function with correct and incorrect data
     * @group fast
     * @dataProvider providerMax
     */
    public function testMax($var, $value, $expected)
    {
        // the filtering
        $result = $this->filterMax($var, $value);
        
        // asserts
        $this->assertEquals($result, $expected);
    }
    
    public function providerMax()
    {
        return [
            [1,     2, 1],
            [5,     2, 2],
            [null,  2, 2]
        ];
    }
    
    /**
     * Test the date-after filtering function with correct and incorrect data
     * @group fast
     * @dataProvider providerDateAfter
     */
    public function testDateAfter($var, $value, $expected)
    {
        // the filtering
        $result = $this->filterAfter($var, $value);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerDateAfter()
    {
        return [
            ['2018-06-03 10:00:00', '2018-06-28 00:00:00', '2018-06-03 10:00:00'],
            ['2018-07-04 11:11:11', '2018-06-28 00:00:00', '2018-06-28 00:00:00'],
            [null,                  '2018-06-28 00:00:00', '2018-06-28 00:00:00']
        ];
    }
    
    /**
     * Test the interval filtering function with correct and incorrect data
     * @group fast
     * @dataProvider providerInterval
     */
    public function testInterval($var, $value1, $value2, $expected)
    {
        // the filtering
        $result = $this->filterInterval($var, $value1, $value2);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerInterval()
    {
        return[
            [1,     2, 4, 2],
            [3,     2, 4, 3],
            [5,     2, 4, 4],
            [null,  2, 4, 2]
        ];
    }
    
    /**
     * Test the date-between filtering function with correct and incorrect data
     * @group fast
     * @dataProvider providerDateBetween
     */
    public function testDateBetween($var, $value1, $value2, $expected)
    {
        // the filtering
        $result = $this->filterDateBetween($var, $value1, $value2);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerDateBetween()
    {
        return [
            ['2018-06-03 10:00:00', '2018-06-28 00:00:00', '2018-07-10 00:00:00', '2018-06-28 00:00:00'],
            ['2018-07-04 11:11:11', '2018-06-28 00:00:00', '2018-07-10 00:00:00', '2018-07-04 11:11:11'],
            ['2018-10-20 11:10:10', '2018-06-28 00:00:00', '2018-07-10 00:00:00', '2018-07-10 00:00:00']
        ];
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
     * @dataProvider providerSetOfValues
     */
    public function testSetOfValues($var, $set, $expected)
    {
        // the filtering
        $result = $this->filterSetOfValues($var, $set);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerSetOfValues()
    {
        return[
            [1,         [2, 4, 6],                  false],
            [2,         [2, 4, 6],                  2],
            [4,         [2, 4, 6],                  4],
            [9,         [2, 4, 6],                  false],
            ['red',     ['red', 'green', 'blue'],   'red'],
            ['yellow',  ['red', 'green', 'blue'],   false]
        ];
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
    
    /**
     * Test email validation
     * @group fast
     * @dataProvider providerIsEmail
     */
    public function testIsEmail($var, $expected)
    {
        // the validating
        $result = $this->isEmail($var);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerIsEmail()
    {
        return[
            ['gabipatru@gmail.com', true],
            ['gabipatru@gmail',     false],
            ['gabipatru',           false],
            ['',                    false],
            [null,                  false]
        ];
    }
    
    /**
     * Test Domain validation
     * @group fast
     * @dataProvider providerIsDomain
     */
    public function testIsDomain($var, $expected)
    {
        // the validating
        $result = $this->isDomain($var);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerIsDomain()
    {
        return[
            ['www.st.ro',               true],
            ['st.ro',                   true],
            ['.ro',                     false],
            ['www.st.ro/index.html',    true],
            ['',                        false],
            [null,                      false]
        ];
    }
    
    /**
     * Test ip validation
     * @group fast
     * @dataProvider providerIsIp
     */
    public function testIsIp($var, $expected)
    {
        // the validation
        $result = $this->isIp($var);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerIsIp()
    {
        return [
            ['1.1.1.1',         true],
            ['192.168.10.21',   true],
            ['255.255.255.255', true],
            ['192.168.10.',     false],
            ['192',             false],
            ['',                false],
            [null,              false]
        ];
    }
    
    /**
     * Test mac validation
     * @group fast
     * @dataProvider providerIsMac
     */
    public function testIsMac($var, $expected)
    {
        // the validation
        $result = $this->isMac($var);
        
        // assert
        $this->assertEquals($expected, $result);
    }
    
    public function providerIsMac()
    {
        return[
            ['00-14-22-01-23-45',   true],
            ['00-14',               false],
            ['123',                 false],
            ['',                    false],
            [null,                  false]
        ];
    }
    
    /**
     * Test url validation
     * @group fast
     * @dataProvider providerIsUrl
     */
    public function testIsUrl($var, $expected)
    {
        // the validation
        $result = $this->isUrl($var);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerIsUrl()
    {
        return[
            ['http://www.st.ro',            true],
            ['https://st.ro',               true],
            ['.ro',                         false],
            ['http://www.st.ro/index.html', true],
            ['',                            false],
            [null,                          false]
        ];
    }
    
    /**
     * Test unsigned int validation
     * @group fast
     * @dataProvider providerIsUnsigned
     */
    public function testIsUnsigned($var, $expected)
    {
        // the validation
        $result = $this->isUnsigned($var);
        
        // asserts
        $this->assertEquals($expected, $result);
    }
    
    public function providerIsUnsigned()
    {
        return[
            ['123',     true],
            ['1',       true],
            ['-2',      false],
            ['1.12',    false],
            ['abc',     false],
            ['',        false],
            [null,      false]
        ];
    }
    
    /**
     * Test all sort of complex filters
     * @group fast
     * @dataProvider providerComplexFilter
     */
    public function testComplexFilter($var, $filter, $expected)
    {
        $result = $this->filter($var, $filter);
        
        $this->assertEquals($expected, $result);
    }
    
    public function providerComplexFilter()
    {
        return [
            [0,     'int|min[1]',       1],
            [null,  'int|min[1]',       1],
            [4,     'int|min[1]',       4],
            [10,    'int|max[5]',       5],
            ['abc', 'int|max[6]',       6],
            [3,     'int|max[10]',      3],
            [0,     'int|set[1,3,5]',   false],
            [3,     'int|set[1,3,5]',   3],
            [1,     'int|interval[2-4]',2],
            ['a>1', 'string|clean_html',    'a&gt;1']
        ];
    }
}
