<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../../AbstractTest.php');

class UserGroup extends AbstractTest
{
    /**
     * Basic test for UserGroup
     * @group fast
     */
    public function testBasic() {
        $UserGroup = new \UserGroup();
        
        $this->assertInstanceOf(\UserGroup::class, $UserGroup);
    }
    
    /**
     * Check if the email log table exists
     * @group slow
     */
    public function testBasicDB() {
        $this->setUpDB(['users']);
        
        $Migration = new \Migration();
        $Tables = $Migration->getTables();
        
        $this->assertTrue(in_array('user_group', $Tables->collectionColumn('tablesinmvctest')));
    }
    
    /**
     * Test Adding a record to DB and fetch records from DB
     * @group slow
     * @depends testBasicDB
     */
    public function testAddToDB() {
        $UserGroup = new \UserGroup();
        
        // add some data to db
        $data = new \SetterGetter();
        $data->setName('test');
        $data->setDescription('desc');
        $data->setStatus('online');
        
        $UserGroup->Add($data);
        
        // check if the data is in db
        $filters = [ 'name' => 'test' ];
        $Collection = $UserGroup->Get($filters);
        
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
        $UserGroup = new \UserGroup();
        
        // get data from db
        $filters = [ 'name' => 'test' ];
        $Collection = $UserGroup->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getUserGroupId();
        
        $data = new \SetterGetter();
        $data->setName('xtest1');
        
        $UserGroup->Edit($id, $data);
        
        // check if the data was edited
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $UserGroup->Get($filters);
        
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
        $UserGroup = new \UserGroup();
        
        // get data from db
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $UserGroup->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getUserGroupId();
        
        $UserGroup->Delete($id);
        
        // check if the data was edited
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $UserGroup->Get($filters);
        
        $this->assertCount(0, $Collection);
    }
}