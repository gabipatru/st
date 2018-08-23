<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../../AbstractTest.php');

class Migration extends AbstractTest
{
    /**
     * Test the version incrementing
     * @group fast
     */
    public function testGetNextVersion() {
        $Migration = new \Migration();
        
        $this->assertInstanceOf(\Migration::class, $Migration);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '000' ]);
        $this->assertEquals('001', $nextVersion);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '004' ]);
        $this->assertEquals('005', $nextVersion);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '009' ]);
        $this->assertEquals('010', $nextVersion);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '010' ]);
        $this->assertEquals('011', $nextVersion);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '015' ]);
        $this->assertEquals('016', $nextVersion);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '019' ]);
        $this->assertEquals('020', $nextVersion);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '099' ]);
        $this->assertEquals('100', $nextVersion);
        
        $nextVersion = $this->invokeMethod($Migration, 'getNextMigration', [ '100' ]);
        $this->assertEquals('101', $nextVersion);
    }
    
    /**
     * Check if the migration table exists
     * @group slow
     */
    public function testBasicDB() {
        $this->setUpDB(['migrations']);
        
        $Migration = new \Migration();
        $Tables = $Migration->getTables();
        
        $this->assertTrue(in_array('_migration', $Tables->collectionColumn('tablesinmvctest')));
    }
    
    /**
     * Test Adding a record to DB and fetch records from DB
     * @group slow
     * @depends testBasicDB
     */
    public function testAddToDB() {
        $Migration = new \Migration();
        
        // add some data to db
        $data = new \SetterGetter();
        $data->setName('test1');
        $data->setVersion('001');
        
        $Migration->Add($data);
        
        // check if the data is in db
        $filters = [ 'name' => 'test1' ];
        $Collection = $Migration->Get($filters);
        
        $this->assertCount(1, $Collection);
        $Item = $Collection->getItem();
        $this->assertEquals('test1', $Item->getName());
        $this->assertEquals('001', $Item->getVersion());
    }
    
    /**
     * Test editing a record in DB and fetching it
     * @group slow
     * @depends testAddToDB
     */
    public function testEditInDB() {
        $Migration = new \Migration();
        
        // get data from db
        $filters = [ 'name' => 'test1' ];
        $Collection = $Migration->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getMigrationId();
        
        $data = new \SetterGetter();
        $data->setName('xtest1');
        
        $Migration->Edit($id, $data);
        
        // check if the data was edited
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $Migration->Get($filters);
        
        $this->assertCount(1, $Collection);
        
        $Item = $Collection->getItem();
        
        $this->assertEquals('xtest1', $Item->getName());
        $this->assertEquals('001', $Item->getVersion());
    }
    
    /**
     * Test deleting an item in the db
     * @group slow
     * @depends testEditInDB
     */
    public function testDeleteInDB() {
        $Migration = new \Migration();
        
        // get data from db
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $Migration->Get($filters);
        $Item = $Collection->getItem();
        $id = $Item->getMigrationId();
        
        $Migration->Delete($id);
        
        // check if the data was edited
        $filters = [ 'name' => 'xtest1' ];
        $Collection = $Migration->Get($filters);
        
        $this->assertCount(0, $Collection);
    }
    
    /**
     * Test deploying a new migration in an existing module
     * migration sql is an array
     * @group slow
     */
    public function testDeployMigrations1() {
        $this->setUpDB(['migrations']);
        
        // mock what we need
        $MockMigrations = $this->getMockBuilder('\Migration')
        ->setMethods([ 'fetchMigrationSQL', 'checkMigrationFile' ])
        ->getMock();
        $MockMigrations->method('fetchMigrationSQL')->willReturn( ['SHOW TABLES'] );
        $MockMigrations->method('checkMigrationFile')
        ->will($this->returnValueMap( [
            [MIGRATIONS_DIR. '/' .'migrations', '002', true],
            [MIGRATIONS_DIR. '/' .'migrations', '003', false]
        ] ));
        
        // run the migration
        $this->invokeMethod($MockMigrations, 'deployMigrations', [ 'migrations', '001' ]);
        
        // check if the migration was run
        $MigrationLog = new \MigrationLog();
        $filters = [ 'migration_id' => 1, 'query' => 'SHOW TABLES' ];
        $Collection = $MigrationLog->Get($filters, []);
        
        $filters = [ 'name' => 'migrations' ];
        $MigrationCollection = $MockMigrations->Get($filters, []);
        
        // asserts
        $this->assertInstanceOf(\Collection::class, $Collection);
        $this->assertEquals(1, $Collection->getItemsNo());
        $this->assertEquals('SHOW TABLES', $Collection->getItem()->getQuery());
        $this->assertEquals('002', $MigrationCollection->getItem()->getVersion());
    }
    
    /**
     * Test deploying a new migration in an existing module
     * migration sql is a string
     * @group slow
     */
    public function testDeployMigrations2() {
        $this->setUpDB(['migrations']);
        
        // mock what we need
        $MockMigrations = $this->getMockBuilder('\Migration')
        ->setMethods([ 'fetchMigrationSQL', 'checkMigrationFile' ])
        ->getMock();
        $MockMigrations->method('fetchMigrationSQL')->willReturn('SHOW TABLES');
        $MockMigrations->method('checkMigrationFile')
        ->will($this->returnValueMap( [
            [MIGRATIONS_DIR. '/' .'migrations', '002', true],
            [MIGRATIONS_DIR. '/' .'migrations', '003', false]
        ] ));
        
        // run the migration
        $this->invokeMethod($MockMigrations, 'deployMigrations', [ 'migrations', '001' ]);
        
        // check if the migration was run
        $MigrationLog = new \MigrationLog();
        $filters = array('migration_id' => 1, 'query' => 'SHOW TABLES');
        $Collection = $MigrationLog->Get($filters, []);
        
        $filters = [ 'name' => 'migrations' ];
        $MigrationCollection = $MockMigrations->Get($filters, []);
        
        // asserts
        $this->assertInstanceOf(\Collection::class, $Collection);
        $this->assertEquals(1, $Collection->getItemsNo());
        $this->assertEquals('SHOW TABLES', $Collection->getItem()->getQuery());
        $this->assertEquals('002', $MigrationCollection->getItem()->getVersion());
    }
    
    /**
     * Test deploying a new migration in a new module
     * migration sql is an array
     * @group slow
     */
    public function testDeployMigrations3() {
        $this->setUpDB(['migrations']);
        
        // mock what we need
        $MockMigrations = $this->getMockBuilder('\Migration')
        ->setMethods([ 'fetchMigrationSQL', 'checkMigrationFile' ])
        ->getMock();
        $MockMigrations->method('fetchMigrationSQL')->willReturn('SHOW TABLES');
        $MockMigrations->method('checkMigrationFile')
        ->will($this->returnValueMap( [
            [MIGRATIONS_DIR. '/' .'phpunit_migration_test', '001', true],
            [MIGRATIONS_DIR. '/' .'phpunit_migration_test', '002', false]
        ] ));
        
        // run the migration
        $this->invokeMethod($MockMigrations, 'deployMigrations', [ 'phpunit_migration_test', '000' ]);
        
        // check if the migration was run
        $MigrationLog = new \MigrationLog();
        $filters = array('migration_id' => 2, 'query' => 'SHOW TABLES');
        $Collection = $MigrationLog->Get($filters, []);
        
        $filters = [ 'name' => 'phpunit_migration_test' ];
        $MigrationCollection = $MockMigrations->Get($filters, []);
        
        // asserts
        $this->assertInstanceOf(\Collection::class, $Collection);
        $this->assertEquals(1, $Collection->getItemsNo());
        $this->assertEquals('SHOW TABLES', $Collection->getItem()->getQuery());
        $this->assertEquals('001', $MigrationCollection->getItem()->getVersion());
    }
}