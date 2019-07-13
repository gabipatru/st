<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../../AbstractTest.php');

class Category extends AbstractTest
{
    /**
     * Basic test for Category
     * @group fast
     */
    public function testBasic() {
        $Category = new \Category();
        
        $this->assertInstanceOf(\Category::class, $Category);
    }
    
    /**
     * Check if the email log table exists
     * @group slow
     */
    public function testBasicDB() {
        $this->setUpDB(['category']);
        
        $Migration = new \Migration();
        $Tables = $Migration->getTables();
        
        $this->assertTrue(in_array('category', $Tables->collectionColumn('tablename')));
    }
    
    /**
     * Test Adding a record to DB and fetch records from DB
     * @group slow
     * @depends testBasicDB
     */
    public function testAddToDB() {
        $Category = new \Category();
        
        // add some data to db
        $data = new \SetterGetter();
        $data->setName('test');
        $data->setDescription('desc');
        $data->setStatus('online');
        
        $Category->Add($data);
        
        // check if the data is in db
        $filters = [ 'name' => 'test' ];
        $Collection = $Category->Get($filters);
        
        $this->assertCount(1, $Collection);
        $Item = $Collection->getItem();
        $this->assertEquals('online', $Item->getStatus());
        $this->assertEquals('test', $Item->getName());
        $this->assertEquals('desc', $Item->getDescription());
    }
    
    /**
     * Test editing a record in DB and fetching it
     * @group slow
     * @depends testAddToDB
     */
    public function testEditInDB() {
        $Category = new \Category();
        
        // get data from db
        $filters = [ 'name' => 'test' ];
        $Collection = $Category->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getCategoryId();
        
        $data = new \SetterGetter();
        $data->setName('xtest1');
        
        $Category->Edit($id, $data);
        
        // check if the data was edited
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $Category->Get($filters);
        
        $this->assertCount(1, $Collection);
        
        $Item = $Collection->getItem();
        
        $this->assertEquals('online', $Item->getStatus());
        $this->assertEquals('desc', $Item->getDescription());
        $this->assertEquals('xtest1', $Item->getName());
    }
    
    /**
     * Test deleting an item in the db
     * @group slow
     * @depends testEditInDB
     */
    public function testDeleteInDB() {
        $Category = new \Category();
        
        // get data from db
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $Category->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getCategoryId();
        
        $Category->Delete($id);
        
        // check if the data was edited
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $Category->Get($filters);
        
        $this->assertCount(0, $Collection);
    }
}