<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../../AbstractTest.php');

/**
 * Test some basic functionality of db data model
 * We are testing it with the help of User model
 */
class dbDataModel extends AbstractTest
{
    /**
     * Basic test
     * @group fast
     */
    public function testBasic()
    {
        $model = new \User();
        $this->assertInstanceOf(\dbDataModel::class, $model);
        $this->assertEquals('user', $model->getTableName());
    }
    
    /**
     * Test columnNames with one field
     * @group fast
     */
    public function testColumnNamesOneField()
    {
        // setup data
        $model = new \User();
        
        $data = new \SetterGetter();
        $data->setFirstName('john');
        
        // run test
        $columns = $this->invokeMethod($model, 'columnNames', [ get_object_vars($data) ]);
        
        // asserts
        $this->assertCount(1, $columns);
        $this->assertTrue(array_key_exists('first_name', $columns));
    }
    
    /**
     * Test columnNames with 3 fields
     * @group fast
     */
    public function testColumnNamesThreeFields()
    {
        // setup data
        $model = new \User();
        
        $data = new \SetterGetter();
        $data->setFirstName('john');
        $data->setLastName('doe');
        $data->setStatus('true');
        
        // run test
        $columns = $this->invokeMethod($model, 'columnNames', [ get_object_vars($data) ]);
        
        // asserts
        $this->assertCount(3, $columns);
        $this->assertTrue(array_key_exists('first_name', $columns));
        $this->assertTrue(array_key_exists('last_name', $columns));
        $this->assertTrue(array_key_exists('status', $columns));
    }
    
    /**
     * Test columnNames with all fields
     * @group fast
     */
    public function testColumnNamesAllFields()
    {
        // setup data
        $model = new \User();
        
        // values are not important, they don't have to be correct
        $data = new \SetterGetter();
        $data->setUserId(1);
        $data->setEmail('testemail');
        $data->setUsername('user');
        $data->setPassword('pass');
        $data->setFirstName('john');
        $data->setLastName('doe');
        $data->setStatus('true');
        $data->setIsAdmin(1);
        $data->setLastLogin(5);
        $data->setCreatedAt('date');
        
        // run test
        $columns = $this->invokeMethod($model, 'columnNames', [ get_object_vars($data) ]);
        
        // asserts
        $this->assertCount(10, $columns);
        $this->assertTrue(array_key_exists('user_id', $columns));
        $this->assertTrue(array_key_exists('username', $columns));
        $this->assertTrue(array_key_exists('password', $columns));
        $this->assertTrue(array_key_exists('first_name', $columns));
        $this->assertTrue(array_key_exists('last_name', $columns));
        $this->assertTrue(array_key_exists('status', $columns));
        $this->assertTrue(array_key_exists('is_admin', $columns));
        $this->assertTrue(array_key_exists('last_login', $columns));
        $this->assertTrue(array_key_exists('created_at', $columns));
    }
    
    /**
     * Test columnNames with 3 fields one of them being a typo
     * @group fast
     */
    public function testColumnNamesThreeFieldsTypo()
    {
        // setup data
        $model = new \User();
        
        $data = new \SetterGetter();
        $data->setFirstName('john');
        $data->setLastNamee('doe');
        $data->setStatus('true');
        
        // run test
        $columns = $this->invokeMethod($model, 'columnNames', [ get_object_vars($data) ]);
        
        // asserts
        $this->assertCount(2, $columns);
        $this->assertTrue(array_key_exists('first_name', $columns));
        $this->assertTrue(array_key_exists('status', $columns));
    }
    
    /**
     * Test columnNames with 3 fields one of them being null
     * @group fast
     */
    public function testColumnNamesThreeFieldsNull()
    {
        // setup data
        $model = new \User();
        
        $data = new \SetterGetter();
        $data->setFirstName('john');
        $data->setLastName('doe');
        $data->setStatus(null);
        
        // run test
        $columns = $this->invokeMethod($model, 'columnNames', [ get_object_vars($data) ]);
        
        // asserts
        $this->assertCount(3, $columns);
        $this->assertTrue(array_key_exists('first_name', $columns));
        $this->assertTrue(array_key_exists('last_name', $columns));
        $this->assertTrue(array_key_exists('status', $columns));
    }
}