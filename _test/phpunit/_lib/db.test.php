<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class db extends AbstractTest
{
    /**
     * Test if the class is a singleton
     * @group fast
     */
    public function testSingleton()
    {
        $db1 = \db::getSingleton();
        $db1->setDebug(false);
        
        $db2 = \db::getSingleton();
        $db2->setDebug(true);
        
        // asserts
        $this->assertSame($db1, $db2);
        $this->assertTrue($db1->getDebug());
        $this->assertTrue($db2->getDebug());
    }
    
    /**
     * Test seatchFilter with a single value
     * @group fast
     */
    public function testSearchFilter()
    {
        $db = \db::getSingleton();
        $options = [
            'search' => 'value to search',
            'search_fields' => ['field1']
        ];
        
        // the test
        list($sql, $aParams) = $db->searchFilter($options);
        
        // asserts
        $this->assertEquals(" AND(`field1` LIKE ?)", $sql);
        $this->assertCount(1, $aParams);
        $this->assertEquals('%value to search%', $aParams[0]);
    }
    
    /**
     * Test searchFilter with multiple fields to search into
     * @group fast
     */
    public function testSearchFilterMultipleFields()
    {
        $db = \db::getSingleton();
        $options = [
            'search' => 'value to search',
            'search_fields' => ['field1', 'field2', 'field3']
        ];
        
        // the test
        list($sql, $aParams) = $db->searchFilter($options);
        
        //asserts
        $this->assertEquals(" AND(`field1` LIKE ? OR `field2` LIKE ? OR `field3` LIKE ?)", $sql);
        $this->assertCount(3, $aParams);
        $this->assertEquals('%value to search%', $aParams[0]);
        $this->assertEquals('%value to search%', $aParams[1]);
        $this->assertEquals('%value to search%', $aParams[2]);
    }
    
    /**
     * Test filters function with simple values
     * @group fast
     * @dataProvider providerFilters
     */
    public function testFilters($filters, $expectedSql, $expectedValue, $expectedCount)
    {
        $db = \db::getSingleton();
        
        // the test
        list($sql, $aParams) = $db->filters($filters);
        
        // asserts
        $this->assertEquals($expectedSql, $sql);
        $this->assertCount($expectedCount, $aParams);
        if ($expectedCount != 0) {
            $this->assertEquals($expectedValue, $aParams[0]);
        }
    }
    
    public function providerFilters()
    {
        return [
            [['field1' => 'value1'], " 1=1 AND `field1` = ? ", 'value1', 1],
            [['field3' => null], " 1=1 AND `field3` IS NULL ", null, 0],
            [['f1' => [1,2,3]], " 1=1 AND `f1` IN (?,?,?) ", 1, 3]
        ];
    }
    
    /**
     * Test filters with serveral fields
     * @group fast
     */
    public function testFiltersComplex()
    {
        $db = \db::getSingleton();
        $filters = [
            'field1' => 'value1',
            'fx' => null,
            'f1' => [1, 2, 3]
        ];
        
        // the test
        list($sql, $aParams) = $db->filters($filters);
        
        // asserts
        $this->assertEquals(" 1=1 AND `field1` = ?  AND `fx` IS NULL  AND `f1` IN (?,?,?) ", $sql);
        $this->assertCount(4, $aParams);
        $this->assertEquals('value1', $aParams[0]);
        $this->assertEquals(1, $aParams[1]);
        $this->assertEquals(2, $aParams[2]);
        $this->assertEquals(3, $aParams[3]);
    }
}