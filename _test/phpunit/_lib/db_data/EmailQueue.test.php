<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../../AbstractTest.php');

class EmailQueue extends AbstractTest
{
    /**
     * Basic test for EmailQueue
     * @group fast
     */
    public function testBasic() {
        $EmailQueue = new \EmailQueue();
        
        $this->assertInstanceOf(\EmailQueue::class, $EmailQueue);
    }
    
    /**
     * Check if the email log table exists
     * @group slow
     */
    public function testBasicDB() {
        $this->setUpDB(['email']);
        
        $Migration = new \Migration();
        $Tables = $Migration->getTables();
        
        $this->assertTrue(in_array('email_queue', $Tables->collectionColumn('tablesinmvctest')));
    }
    
    /**
     * Test Adding a record to DB and fetch records from DB
     * @group slow
     * @depends testBasicDB
     */
    public function testAddToDB() {
        $EmailQueue = new \EmailQueue();
        
        // add some data to db
        $data = new \SetterGetter();
        $data->setTo('test@st.ro');
        $data->setSubject('test');
        $data->setBody('test body');
        $data->setPriority(11);
        $data->setStatus(\EmailQueue::STATUS_SENT);
        $data->setSendAttempts(2);
        $data->setUpdatedAt('2018-05-01 10:00:00');
        
        $EmailQueue->Add($data);
        
        // check if the data is in db
        $filters = [ 'subject' => 'test' ];
        $Collection = $EmailQueue->Get($filters);
        
        $this->assertCount(1, $Collection);
        $Item = $Collection->getItem();
        $this->assertEquals('test@st.ro', $Item->getTo());
        $this->assertEquals('test', $Item->getSubject());
        $this->assertEquals('test body', $Item->getBody());
        $this->assertEquals(11, $Item->getPriority());
        $this->assertEquals(\EmailQueue::STATUS_SENT, $Item->getStatus());
        $this->assertEquals(2, $Item->getSendAttempts());
        $this->assertEquals('2018-05-01 10:00:00', $Item->getUpdatedAt());
    }
    
    /**
     * Test editing a record in DB and fetching it
     * @group slow
     * @depends testAddToDB
     */
    public function testEditInDB() {
        $EmailQueue = new \EmailQueue();
        
        // get data from db
        $filters = [ 'subject' => 'test' ];
        $Collection = $EmailQueue->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getEmailQueueId();
        
        $data = new \SetterGetter();
        $data->setSubject('xtest1');
        
        $EmailQueue->Edit($id, $data);
        
        // check if the data was edited
        $filters = [ 'subject' => 'xtest1' ];
        $Collection = $EmailQueue->Get($filters);
        
        $this->assertCount(1, $Collection);
        
        $Item = $Collection->getItem();
        
        $this->assertEquals('test@st.ro', $Item->getTo());
        $this->assertEquals('xtest1', $Item->getSubject());
        $this->assertEquals('test body', $Item->getBody());
        $this->assertEquals(11, $Item->getPriority());
        $this->assertEquals(\EmailQueue::STATUS_SENT, $Item->getStatus());
        $this->assertEquals(2, $Item->getSendAttempts());
        $this->assertEquals('2018-05-01 10:00:00', $Item->getUpdatedAt());
    }
    
    /**
     * Test deleting an item in the db
     * @group slow
     * @depends testEditInDB
     */
    public function testDeleteInDB() {
        $EmailQueue = new \EmailQueue();
        
        // get data from db
        $filters = [ 'subject' => 'xtest1' ];
        $Collection = $EmailQueue->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getEmailQueueId();
        
        $EmailQueue->Delete($id);
        
        // check if the data was edited
        $filters = [ 'subject' => 'xtest1' ];
        $Collection = $EmailQueue->Get($filters);
        
        $this->assertCount(0, $Collection);
    }
}