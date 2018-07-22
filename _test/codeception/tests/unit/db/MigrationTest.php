<?php

namespace Test;

require_once(__DIR__ .'/../AbstractTest.php');


/**
 * Test the migration class
 */
class Migration extends AbstractTest
{
    
    /**
     * Test the version incrementing
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
     * Test deploying a new migration in an existing module
     * migration sql is an array
     */
    public function testDeployMigrations1() {
        $this->setUpDB();
        
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
     */
    public function testDeployMigrations2() {
        $this->setUpDB();
        
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
     */
    public function testDeployMigrations3() {
        $this->setUpDB();
        
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
        $filters = array('migration_id' => 6, 'query' => 'SHOW TABLES');
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