<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/AbstractTest.php');
require_once(TRAITS_DIR .'/Log.trait.php');

/**
 * Test the Log trait
 */
class LogTest extends AbstractTest
{
    use \Log;

    /**
     * Test if the log is being created
     * @group fast
     */
    public function testLogCreation()
    {
        // if the file was previously created, delete it
        if (file_exists(LOG_PATH. '/' .get_class($this) .'.log')) {
            unlink(LOG_PATH. '/' .get_class($this) .'.log');
        }

        // the test
        $this->logMessage('this is a test');

        // asserts
        $this->assertTrue(file_exists(LOG_PATH. '/' .get_class($this) .'.log'));
        $this->assertGreaterThan(0, filesize(LOG_PATH. '/' .get_class($this) .'.log'));
    }

    /**
     * Test if the log is being created when using a custom log
     * @group fast
     */
    public function testCustomLogCreation()
    {
        $logName = 'test.log';

        // if the file was previously created, delete it
        if (file_exists(LOG_PATH. '/' .$logName)) {
            unlink(LOG_PATH. '/' .$logName);
        }

        // the test
        $this->logMessage('this is a test', $logName);

        // asserts
        $this->assertTrue(file_exists(LOG_PATH. '/' .get_class($this) .'.log'));
        $this->assertGreaterThan(0, filesize(LOG_PATH. '/' .get_class($this) .'.log'));
    }
}
