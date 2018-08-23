<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error\Error;

require_once(__DIR__ .'/../AbstractTest.php');

class db extends AbstractTest
{
    private static $bTestTableInitialised = false;
    
    /**
     * Test if the class is a singleton
     * @group fast
     */
    public function testSingleton()
    {
        $db1 = \db::getSingleton();
        $db1->setDebug(true);
        
        $db2 = \db::getSingleton();
        $db2->setDebug(false);
        
        // asserts
        $this->assertSame($db1, $db2);
        $this->assertFalse($db1->getDebug());
        $this->assertFalse($db2->getDebug());
    }
    
    /**
     * Test database connection with correct credentials
     * @group fast
     */
    public function testDbConnectionGoodCredentials() {
        $this->defineDebuggerAgent();
        
        $db = \db::getSingleton();
        $db->connect(self::DB_HOST, self::DB_DATABASE, self::DB_USER, self::DB_PASS);
        
        // assert no exception happned
        $this->assertTrue(true);
    }
    
    /**
     * Test database connection with correct credentials
     * @group fast
     */
    public function testDbConnectionBadCredentials() {
        $this->defineDebuggerAgent();
        
        $this->expectException(\PDOException::class);
        
        $db = \db::getSingleton();
        $db->connect(self::DB_HOST, self::DB_DATABASE, self::DB_USER, 'qwe');
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
    
    /**
     * Test nextId function
     * @group slow
     */
    public function testNextId()
    {
        $this->setUpDB(['test']);
        
        $db = \db::getSingleton();
        
        // the test
        $nextId = $db->nextId(\Test::TABLE_NAME);
        
        // assert
        $this->assertEquals(1, $nextId);
        
        self::$bTestTableInitialised = true;
    }
    
    /**
     * Check if simple transactions work
     * @group slow
     */
    public function testSimpleTransaction()
    {
        if (! self::$bTestTableInitialised) {
            $this->markTestSkipped();
        }
        
        $db = \db::getSingleton();
        
        // check how many items are currently in Test table
        $Test = new \Test();
        $Col = $Test->Get();
        
        $oldRows = $Col->getItemsNo();
        
        $this->assertEquals(0, $db->transactionLevel());
        
        // add an itemm
        $db->startTransaction();
        $Item = new \SetterGetter();
        $Item->setName('test');
        
        $Test->Add($Item);
        
        // check number of items again
        $Col = $Test->Get();
        $rows = $Col->getItemsNo();
        
        $this->assertEquals(1, $db->transactionLevel());
        $this->assertEquals($oldRows + 1, $rows);
        
        // rollback transaction, check again
        $db->rollbackTransaction();
        
        $Col = $Test->Get();
        $rows = $Col->getItemsNo();
        
        $this->assertEquals(0, $db->transactionLevel());
        $this->assertEquals($oldRows, $rows);
    }
    
    /**
     * Test if nested transactions work
     * @group slow
     */
    public function testNestedTransaction()
    {
        if (! self::$bTestTableInitialised) {
            $this->markTestSkipped();
        }
        
        $db = \db::getSingleton();
        
        // check how many items are currently in Test table
        $Test = new \Test();
        $Col = $Test->Get();
        
        $oldRows = $Col->getItemsNo();
        
        $this->assertEquals(0, $db->transactionLevel());
        
        // add an itemm within one transaction
        $db->startTransaction();
        $Item = new \SetterGetter();
        $Item->setName('test');
        
        $Test->Add($Item);
        
        // check number of items again
        $Col = $Test->Get();
        $rows = $Col->getItemsNo();
        
        $this->assertEquals(1, $db->transactionLevel());
        $this->assertEquals($oldRows + 1, $rows);
        
        // add second item in second transaction
        $db->startTransaction();
        $Item = new \SetterGetter();
        $Item->setName('test2');
        
        $Test->Add($Item);
        
        // check if we have 2
        $Col = $Test->Get();
        $rows = $Col->getItemsNo();
        
        $this->assertEquals(2, $db->transactionLevel());
        $this->assertEquals($oldRows + 2, $rows);
        
        // rollback fist transaction, make check
        $db->rollbackTransaction();
        
        $Col = $Test->Get();
        $rows = $Col->getItemsNo();
        
        $this->assertEquals(1, $db->transactionLevel());
        $this->assertEquals($oldRows + 1, $rows);
        
        // rollback last transaction, make check
        $db->rollbackTransaction();
        
        $Col = $Test->Get();
        $rows = $Col->getItemsNo();
        
        $this->assertEquals(0, $db->transactionLevel());
        $this->assertEquals($oldRows, $rows);
    }
    
    /**
     * Test query function with an invalid query
     * @group fast
     */
    public function testQueryWithEmptySql()
    {
        $db = \db::getSingleton();
        
        $this->expectException(Error::class);
        
        $db->query(0);
    }
    
    /**
     * Test query function with wrong query
     * @group fast
     */
    public function testQueryWithError()
    {
        $db = \db::getSingleton();
        
        $this->expectException(Error::class);
        
        $db->query("x=1");
    }
    
    /**
     * Test if query is outputed when debug is set to true
     * @group fast
     */
    public function testQueryWithDebug()
    {
        $db = \db::getSingleton();
        $db->setDebug(true);
        
        $db->query("SELECT 1");
        
        $db->setDebug(false);
        
        $this->expectOutputString("SELECT 1<pre></pre>");
    }
    
    /**
     * Test if the number of queries is incremented
     * @group fast
     */
    public function testQueryNumber()
    {
        $db = \db::getSingleton();
        $iNumberOfQueries = $db->getQueriesNo();
        
        $db->query("SELECT 1");
        
        $iNewNumberOfQueries = $db->getQueriesNo();
        
        $this->assertEquals($iNumberOfQueries + 1, $iNewNumberOfQueries);
    }
}