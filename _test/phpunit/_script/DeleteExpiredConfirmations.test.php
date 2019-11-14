<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . './../AbstractTest.php';
require_once SCRIPT_DIR . '/DeleteExpiredConfirmations.php';

/**
 * This tests the DeleteExpiredConfirmations class
 */
class DeleteExpiredConfirmations extends AbstractTest
{
    /**
     * Test what happens when there are no confirmations to delete
     * @group fast
     */
    public function testNoConfirmations()
    {
        // init and mock
        $MockCron = $this->getMockBuilder('\Cron\DeleteExpiredConfirmations')
            ->setMethods([ 'displayMsg', 'getExpiredConfirmations', 'deleteConfirmation' ])
            ->getMock();
        $MockCron->expects($this->exactly(2))
            ->method('displayMsg')
            ->willReturn(null);
        $MockCron->expects($this->once())
            ->method('getExpiredConfirmations')
            ->willReturn(new \Collection());
        $MockCron->expects($this->never())
            ->method('deleteConfirmation')
            ->willReturn(null);

        $MockCron->run();
    }

    /**
     * Test what happens when there are 2 confirmations to delete
     * @group fast
     */
    public function test2Confirmations()
    {
        // init and mock
        $MockCron = $this->getMockBuilder('\Cron\DeleteExpiredConfirmations')
            ->setMethods([ 'displayMsg', 'getExpiredConfirmations', 'deleteConfirmation' ])
            ->getMock();
        $MockCron->expects($this->exactly(2))
            ->method('displayMsg')
            ->willReturn(null);
        $collection = new \Collection();
        $collection->add(1, [ 'script' => 'test1' ]);
        $collection->add(2, [ 'script' => 'test2' ]);
        $MockCron->expects($this->once())
            ->method('getExpiredConfirmations')
            ->willReturn($collection);
        $MockCron->expects($this->exactly(2))
            ->method('deleteConfirmation')
            ->willReturn(true);

        $MockCron->run();
    }
}
