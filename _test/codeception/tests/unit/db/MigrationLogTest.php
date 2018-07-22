<?php

namespace Test;

require_once(__DIR__ .'/../AbstractTest.php');

/**
 * Test the migration log class
 */
class MigrationLog extends AbstractTest
{
    /**
     * Basic test for MigrationLog
     */
    public function testBasic() {
        $MigrationLog = new \MigrationLog();
        
        $this->assertInstanceOf(\MigrationLog::class, $MigrationLog);
    }
    
    /**
     * Check if the migration table exists
     */
    public function testBasicDB() {
        $this->setUpDB();
        
        $Migration = new \Migration();
        $Tables = $Migration->getTables();
        
        $this->assertTrue(in_array('migration_log', $Tables->collectionColumn('tablesinmvctest')));
    }
    
    /**
     * Test Adding a record to DB and fetch records from DB
     * @depends testBasicDB
     */
    public function testAddToDB() {
        $MigrationLog = new \MigrationLog();
        
        // add some data to db
        $data = new \SetterGetter();
        $data->setMigrationId(1);
        $data->setQuery('test');
        $data->setDuration('1.300');
        
        $MigrationLog->Add($data);
        
        // check if the data is in db
        $filters = [ 'query' => 'test' ];
        $Collection = $MigrationLog->Get($filters);
        
        $this->assertCount(1, $Collection);
        $Item = $Collection->getItem();
        $this->assertEquals('test', $Item->getQuery());
        $this->assertEquals('1.300', $Item->getDuration());
    }
    
    /**
     * Test editing a record in DB and fetching it
     * @depends testAddToDB
     */
    public function testEditInDB() {
        $MigrationLog = new \MigrationLog();
        
        // get data from db
        $filters = [ 'query' => 'test' ];
        $Collection = $MigrationLog->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getMigrationLogId();
        
        $data = new \SetterGetter();
        $data->setQuery('xtest1');
        
        $MigrationLog->Edit($id, $data);
        
        // check if the data was edited
        $filters = [ 'query' => 'xtest1' ];
        $Collection = $MigrationLog->Get($filters);
        
        $this->assertCount(1, $Collection);
        
        $Item = $Collection->getItem();
        
        $this->assertEquals('xtest1', $Item->getQuery());
        $this->assertEquals('1.300', $Item->getDuration());
    }
    
    /**
     * Test deleting an item in the db
     * @depends testEditInDB
     */
    public function testDeleteInDB() {
        $MigrationLog = new \MigrationLog();
        
        // get data from db
        $filters = [ 'query' => 'xtest1' ];
        $Collection = $MigrationLog->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getMigrationLogId();
        
        $MigrationLog->Delete($id);
        
        // check if the data was edited
        $filters = [ 'query' => 'xtest1' ];
        $Collection = $MigrationLog->Get($filters);
        
        $this->assertCount(0, $Collection);
    }
}