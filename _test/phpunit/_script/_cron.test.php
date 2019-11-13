<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . './../AbstractTest.php';
require_once SCRIPT_DIR . '/_config.php';
require_once SCRIPT_DIR . '/_cron.php';

/**
 * This tests the Cron class and the AbstractCron class it extends
 */
class Cron extends AbstractTest
{
    /**
     * Test if a message is displayed correctly
     * @group fast
     */
    public function testDisplayMsg()
    {
        // init
        $Cron = new \Cron\Cron();
        $Cron->setDebug(true);

        // the test
        ob_start();
        $Cron->displayMsg('test display');
        $result = ob_get_clean();

        // assert
        $this->assertEquals("test display\n", $result);

        ob_start();
        unset($Cron);
        ob_end_clean();
    }

    /**
     * Test if the warning email address is correct
     * @group fast
     */
    public function testGetWarningEmailAddress()
    {
        // init
        $Cron = new \Cron\Cron();
        $Cron->setDebug(true);

        // the test
        $result = $Cron->getWarningEmailAddress();

        // assert
        $this->assertEquals(CRON_WARNING_EMAIL_ADDRESS, $result);

        ob_start();
        unset($Cron);
        ob_end_clean();
    }

    /**
     * Tets what happens when there are no crons to run
     * @group fast
     */
    public function testRunCronNoCrons()
    {
        // init and mock
        $MockCron = $this->getMockBuilder('\Cron\Cron')
            ->setMethods([ 'getCronsToRun', 'runCron', 'displayMsg' ])
            ->getMock();
        $MockCron->expects($this->once())
            ->method('getCronsToRun')
            ->willReturn(new \Collection());
        $MockCron->expects($this->exactly(0))
            ->method('runCron')
            ->willReturn(null);
        $MockCron->expects($this->once())
            ->method('displayMsg')
            ->will($this->returnCallback(function ($msg) {
                $this->assertEquals('No crons to run', $msg);
            }));

        // the test
        $MockCron->run();
    }

    /**
     * Tets what happens when there are 2 crons to run
     * @group fast
     */
    public function testRunCron2Crons()
    {
        // init and mock
        $MockCron = $this->getMockBuilder('\Cron\Cron')
            ->setMethods([
                'getCronsToRun',
                'runCron',
                'displayMsg',
                'calculateNextRuntime',
                'updateCron'
            ])->getMock();
        $MockCron->expects($this->exactly(2))
            ->method('runCron')
            ->willReturn(null);
        $MockCron->expects($this->exactly(2))
            ->method('calculateNextRuntime')
            ->willReturn('1');
        $MockCron->expects($this->exactly(2))
            ->method('updateCron')
            ->willReturn(null);

        $collection = new \Collection();
        $collection->add(1, [ 'script' => 'test1' ]);
        $collection->add(2, [ 'script' => 'test2' ]);
        $MockCron->expects($this->once())
            ->method('getCronsToRun')
            ->willReturn($collection);

        // the test
        $MockCron->run();
    }

    /**
     * Test if the nextRunTime is calculated correctly
     * @group fast
     */
    public function testCalculateNextRuntime()
    {
        // init
        $Cron = new \Cron\Cron();

        $cron = new \SetterGetter();
        $cron->setLastRuntime('2019-11-12 00:00:00');
        $cron->setInterval(1);

        // the test
        $result = $this->invokeMethod($Cron, 'calculateNextRuntime', [ $cron ]);

        // assert
        $this->assertNotNull($result);
    }
}
