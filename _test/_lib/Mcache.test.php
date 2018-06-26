<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class Mcache extends AbstractTest {
    
    /**
     * Test the connection to Memcached
     * @group fast
     */
    public function testConnection() {
        $mem = \Mcache::getSingleton();
        
        $this->assertInstanceOf(\Memcached::class, $mem);
    }
    
    /**
     * Test adding a memcache key and reading it
     * @group fast
     */
    public function testMemcacheKey() {
        $mem = \Mcache::getSingleton();
        
        $mem->set('key1', 'abc');
        
        $this->assertEquals('abc', $mem->get('key1'));
    }
}