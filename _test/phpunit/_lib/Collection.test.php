<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class Collection extends AbstractTest {
    
    /**
     * Test adding some items to a Collection
     * @group fast
     */
    public function testCollection() {
        $Collection = new \Collection();
        
        $row = ['name' => 'apple juice', 'type' => 'natural'];
        $Collection->add(1, $row);
        $row = ['name' => 'coca cola', 'type' => 'synthetic'];
        $Collection->add(2, $row);
        
        // asserts
        $this->assertTrue($Collection instanceof \Collection);
        $this->assertFalse($Collection instanceof Collection);
        $this->assertCount(2, $Collection);
        $this->assertTrue($Collection->getItem($Collection) instanceof \SetterGetter);
        $this->assertEquals('apple juice', $Collection->getById(1)->getName());
        $this->assertEquals('synthetic', $Collection->getById(2)->getType());
    }
    
    /**
     * Create a collection and then transform it into an array
     * @group fast
     */
    public function testCollectionToArray() {
        $Collection = new \Collection();
        
        $row = ['name' => 'apple juice', 'type' => 'natural'];
        $Collection->add(1, $row);
        $row = ['name' => 'coca cola', 'type' => 'synthetic'];
        $Collection->add(2, $row);
        $arr = $Collection->toArray();
        
        // asserts
        $this->assertTrue($Collection instanceof \Collection);
        $this->assertTrue(is_array($arr));
        $this->assertCount(2, $arr);
        
        $this->assertArrayHasKey(1, $arr);
        $this->assertArrayHasKey(2, $arr);
        
        $this->assertTrue(is_array($arr[1]));
        $this->assertTrue(is_array($arr[2]));
        $this->assertArrayHasKey('name', $arr[1]);
        $this->assertArrayHasKey('name', $arr[2]);
        $this->assertArrayHasKey('type', $arr[1]);
        $this->assertArrayHasKey('type', $arr[2]);
        $this->assertEquals('apple juice', $arr[1]['name']);
        $this->assertEquals('synthetic', $arr[2]['type']);
    }
    
    /**
     * Test creating a collection from an array
     * @group fast
     */
    public function testCollectionFromArray() {
        $Collection = new \Collection();
        
        // the array
        $arr = [
            1 => ['name' => 'apple juice', 'type' => 'natural'],
            2 => ['name' => 'coca cola', 'type' => 'synthetic']
        ];
        
        $Collection->fromArray($arr);
        
        // asserts
        $this->assertInstanceOf(\Collection::class, $Collection);
        $this->assertCount(2, $Collection);
        $this->assertInstanceOf(\SetterGetter::class, $Collection->getItem());
        $this->assertEquals('apple juice', $Collection->getById(1)->getName());
        $this->assertEquals('synthetic', $Collection->getById(2)->getType());
    }
    
    /**
     * Try getting an item from an empty collection
     * @group fast
     */
    public function testGetItemEmptyCollection() {
        $Collection = new \Collection();
        
        $Item = $Collection->getItem();
        
        // assert
        $this->assertFalse($Item instanceof \SetterGetter);
        $this->assertFalse($Item);
    }
    
    /**
     * Test getting a collection column
     * @group fast
     */
    public function testCollectionColumn() {
        $Collection = new \Collection();
        
        // the data
        $arr = [
            1 => ['name' => 'apple juice', 'type' => 'natural'],
            2 => ['name' => 'coca cola', 'type' => 'synthetic']
        ];
        
        $Collection->fromArray($arr);
        
        // test collection column
        $arr = $Collection->collectionColumn('type');
        
        $this->assertCount(2, $arr);
        $this->assertEquals($arr[0], 'natural');
        $this->assertEquals($arr[1], 'synthetic');
    }
}