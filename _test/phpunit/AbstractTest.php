<?php
declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../../_config/paths.php');
require_once(CLASSES_DIR .'/mvc.php');

abstract class AbstractTest extends TestCase {
    
    /**
     * DB constants are used for connecting to test database
     */
    const DB_HOST       = 'localhost';
    const DB_USER       = 'st';
    const DB_PASS       = 'qwqwqwqw';
    const DB_DATABASE   = 'mvc_test';
    
    /**
     * Called each time a new test is being run
     */
    public function setUp() {
        //register the autoloading class
        spl_autoload_register('mvc::autoload');
    }
    
    /**
     * Set up the DB by deleting all tables and running the migrations
     * an an empty database
     */
    public function setUpDB($migrations = null) {
        $this->defineDebuggerAgent();
        
        $db = \db::getSingleton();
        $db->connect(self::DB_HOST, self::DB_DATABASE, self::DB_USER, self::DB_PASS);
        
        // delete the existing tables
        $Migration = new \Migration();
        $oTablesCollection = $Migration->getTables();
        
        $db->query("SET FOREIGN_KEY_CHECKS = 0;");
        foreach ($oTablesCollection as $Table) {
            $tableName = $Table->getTablesInMvcTest();
            $db->query("DROP TABLE `".$tableName."`");
        }
        $db->query("SET FOREIGN_KEY_CHECKS = 1;");
        
        // run the migrations on empty db
        $Migration->runMigrations($migrations);
    }
    
    /**
     * Called each time a test ends
     */
    public function tearDown() {
        
    }
    
    protected function defineDebuggerAgent() {
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