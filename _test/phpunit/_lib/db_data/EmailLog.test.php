<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../../AbstractTest.php');

class EmailLog extends AbstractTest
{   
    /**
     * Basic test for EmailLog
     * @group fast
     */
    public function testBasic() {
        $EmailLog = new \EmailLog();
        
        $this->assertInstanceOf(\EmailLog::class, $EmailLog);
    }
    
    /**
     * Check if the email log table exists
     * @group slow
     */
    public function testBasicDB() {
        $this->setUpDB(['email']);
        
        $Migration = new \Migration();
        $Tables = $Migration->getTables();
        
        $this->assertTrue(in_array('email_log', $Tables->collectionColumn('tablesinmvctest')));
    }
    
    /**
     * Test Adding a record to DB and fetch records from DB
     * @group slow
     * @depends testBasicDB
     */
    public function testAddToDB() {
        $EmailLog = new \EmailLog();
        
        // add some data to db
        $data = new \SetterGetter();
        $data->setStatus(\EmailLog::STATUS_SENT);
        $data->setErrorInfo('error info');
        $data->setDebug('debug');
        
        $EmailLog->Add($data);
        
        // check if the data is in db
        $filters = [ 'debug' => 'debug' ];
        $Collection = $EmailLog->Get($filters);
        
        $this->assertCount(1, $Collection);
        $Item = $Collection->getItem();
        $this->assertEquals(\EmailLog::STATUS_SENT, $Item->getStatus());
        $this->assertEquals('error info', $Item->getErrorInfo());
        $this->assertEquals('debug', $Item->getDebug());
    }
    
    /**
     * Test editing a record in DB and fetching it
     * @group slow
     * @depends testAddToDB
     */
    public function testEditInDB() {
        $EmailLog = new \EmailLog();
        
        // get data from db
        $filters = [ 'debug' => 'debug' ];
        $Collection = $EmailLog->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getEmailLogId();
        
        $data = new \SetterGetter();
        $data->setDebug('xtest1');
        
        $EmailLog->Edit($id, $data);
        
        // check if the data was edited
        $filters = [ 'debug' => 'xtest1' ];
        $Collection = $EmailLog->Get($filters);
        
        $this->assertCount(1, $Collection);
        
        $Item = $Collection->getItem();
        
        $this->assertEquals(\EmailLog::STATUS_SENT, $Item->getStatus());
        $this->assertEquals('error info', $Item->getErrorInfo());
        $this->assertEquals('xtest1', $Item->getDebug());
    }
    
    /**
     * Test deleting an item in the db
     * @group slow
     * @depends testEditInDB
     */
    public function testDeleteInDB() {
        $EmailLog = new \EmailLog();
        
        // get data from db
        $filters = [ 'debug' => 'xtest1' ];
        $Collection = $EmailLog->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getEmailLogId();
        
        $EmailLog->Delete($id);
        
        // check if the data was edited
        $filters = [ 'debug' => 'xtest1' ];
        $Collection = $EmailLog->Get($filters);
        
        $this->assertCount(0, $Collection);
    }
}