<?php

declare(strict_types=1);

namespace Test;

use Codeception\Test\Unit;

require_once __DIR__ . '/../../../../_config/paths.php';
require_once CLASSES_DIR . '/mvc.php';

abstract class AbstractTest extends Unit
{
    
    /**
     * DB constants are used for connecting to test database
     */
    const DB_HOST       = 'localhost';
    const DB_USER       = 'st_test';
    const DB_PASS       = 'qwqwqw';
    const DB_DATABASE   = 'surprize_turbo_test';
    
    protected $tester;
    
    /**
     * Called each time a new test is being run
     */
    protected function _before()
    {
        //register the autoloading class
        spl_autoload_register('mvc::autoload');
    }
    
    protected function _after()
    {
    }
    
    /**
     * Set up the DB by deleting all tables and running the migrations
     * an an empty database
     */
    public function setUpDB()
    {
        $this->defineDebuggerAgent();
        
        $db = \db::getSingleton();
        $db->connect(self::DB_HOST, self::DB_DATABASE, self::DB_USER, self::DB_PASS);
        
        // delete the existing tables
        $Migration = new \Migration();

        $db->query("DROP SCHEMA public CASCADE;");
        $db->query("CREATE SCHEMA public;");
        
        // run the migrations on empty db
        $Migration->runMigrations();
    }
    
    protected function defineDebuggerAgent()
    {
        if (!defined('DEBUGGER_AGENT')) {
            define('DEBUGGER_AGENT', 1);
        }
    }
    
    /**
     * Call protected/private method of a class.
     *
     * @param object $object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod($object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $parameters);
    }
}
