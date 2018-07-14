<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class Pagination extends AbstractTest 
{
    /**
     * Test pagination generation with simple data - first page
     * @group fast
     */
    public function testPaginationSimpleFirstPage()
    {
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(1);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(100);
        
        //run the test
        $this->invokeMethod($oPagination, 'compute');
        
        // asserts
        $this->assertEquals(10, $oPagination->getMaxPage());
        $this->assertEquals(0, $oPagination->getPrevPages());
        $this->assertEquals(2, $oPagination->getNextPages());
        $this->assertFalse($oPagination->getFirstPage());
        $this->assertTrue($oPagination->getLastPage());
    }
    
    /**
     * Test pagination generation with simple data - second page
     * @group fast
     */
    public function testPaginationSimpleSecondPage()
    {
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(2);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(100);
        
        //run the test
        $this->invokeMethod($oPagination, 'compute');
        
        // asserts
        $this->assertEquals(10, $oPagination->getMaxPage());
        $this->assertEquals(1, $oPagination->getPrevPages());
        $this->assertEquals(2, $oPagination->getNextPages());
        $this->assertFalse($oPagination->getFirstPage());
        $this->assertTrue($oPagination->getLastPage());
    }
    
    /**
     * Test pagination generation with simple data - third page
     * @group fast
     */
    public function testPaginationSimpleThirdPage()
    {
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(3);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(100);
        
        //run the test
        $this->invokeMethod($oPagination, 'compute');
        
        // asserts
        $this->assertEquals(10, $oPagination->getMaxPage());
        $this->assertEquals(2, $oPagination->getPrevPages());
        $this->assertEquals(2, $oPagination->getNextPages());
        $this->assertTrue($oPagination->getFirstPage());
        $this->assertTrue($oPagination->getLastPage());
    }
    
    /**
     * Test pagination generation with simple data - brefore brefore last page
     * @group fast
     */
    public function testPaginationSimpleBeforeBeforeLastPage()
    {
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(18);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(200);
        
        //run the test
        $this->invokeMethod($oPagination, 'compute');
        
        // asserts
        $this->assertEquals(20, $oPagination->getMaxPage());
        $this->assertEquals(2, $oPagination->getPrevPages());
        $this->assertEquals(2, $oPagination->getNextPages());
        $this->assertTrue($oPagination->getFirstPage());
        $this->assertTrue($oPagination->getLastPage());
    }
    
    /**
     * Test pagination generation with simple data - brefore last page
     * @group fast
     */
    public function testPaginationSimpleBeforeLastPage()
    {
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(19);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(200);
        
        //run the test
        $this->invokeMethod($oPagination, 'compute');
        
        // asserts
        $this->assertEquals(20, $oPagination->getMaxPage());
        $this->assertEquals(2, $oPagination->getPrevPages());
        $this->assertEquals(1, $oPagination->getNextPages());
        $this->assertTrue($oPagination->getFirstPage());
        $this->assertFalse($oPagination->getLastPage());
    }
    
    /**
     * Test pagination generation with simple data - last page
     * @group fast
     */
    public function testPaginationSimpleLastPage()
    {
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(20);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(200);
        
        //run the test
        $this->invokeMethod($oPagination, 'compute');
        
        // asserts
        $this->assertEquals(20, $oPagination->getMaxPage());
        $this->assertEquals(2, $oPagination->getPrevPages());
        $this->assertEquals(0, $oPagination->getNextPages());
        $this->assertTrue($oPagination->getFirstPage());
        $this->assertFalse($oPagination->getLastPage());
    }
    
    /**
     * Test pagination generation with wrong data
     * @group fast
     */
    public function testPaginationWrongData()
    {
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('abc');
        $oPagination->setPage(20);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(200);
        
        $this->assertFalse($this->invokeMethod($oPagination, 'compute'));
        
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(-1);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo(200);
        
        $this->assertFalse($this->invokeMethod($oPagination, 'compute'));
        
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(20);
        $oPagination->setPerPage(null);
        $oPagination->setItemsNo(200);
        
        $this->assertFalse($this->invokeMethod($oPagination, 'compute'));
        
        // init
        $oPagination = new \Pagination();
        $oPagination->setUrl('http://www.st.ro');
        $oPagination->setPage(20);
        $oPagination->setPerPage(10);
        $oPagination->setItemsNo('abc');
        
        $this->assertFalse($this->invokeMethod($oPagination, 'compute'));
    }
}