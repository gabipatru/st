<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . './../AbstractTest.php';
require_once SCRIPT_DIR . '/SendQueuedEmail.php';

/**
 * This tests the SendQueuedEmail class
 */
class SendQueuedEmail extends AbstractTest
{
    /**
     * Test what happens when there are no emails to send
     * @group fast
     */
    public function testNoEmails()
    {
        // init and mock
        $MockDb = $this->getMockBuilder('\db')
            ->disableOriginalConstructor()
            ->getMock();
        $MockCron = $this->getMockBuilder('\Cron\SendQueuedEmail')
            ->setConstructorArgs([$MockDb])
            ->setMethods([ 'displayMsg', 'getEmailsToProcess', 'updateEmailQueue', 'sendEmail' ])
            ->getMock();
        $MockCron->expects($this->once())
            ->method('displayMsg')
            ->willReturn(null);
        $MockCron->expects($this->once())
            ->method('getEmailsToProcess')
            ->willReturn(new \Collection());
        $MockCron->expects($this->never())
            ->method('sendEmail')
            ->willReturn(null);
        $MockCron->expects($this->never())
            ->method('updateEmailQueue')
            ->willReturn(null);

        // the test
        $MockCron->run();
    }

    /**
     * Test what happens when there are 2 emails to send
     * @group fast
     */
    public function testSend2Emails()
    {
        // init and mock
        $MockDb = $this->getMockBuilder('\db')
            ->disableOriginalConstructor()
            ->getMock();
        $MockCron = $this->getMockBuilder('\Cron\SendQueuedEmail')
            ->setConstructorArgs([$MockDb])
            ->setMethods([ 'displayMsg', 'getEmailsToProcess', 'updateEmailQueue', 'sendEmail' ])
            ->getMock();
        $MockCron->expects($this->once())
            ->method('displayMsg')
            ->willReturn(null);
        $collection = new \Collection();
        $collection->add(1, [ 'too' => 'test1@test.com', 'emailQueueId' => 1 ]);
        $collection->add(2, [ 'too' => 'test2@test.com', 'emailQueueId' => 2 ]);
        $MockCron->expects($this->once())
            ->method('getEmailsToProcess')
            ->willReturn($collection);
        $MockCron->expects($this->exactly(2))
            ->method('sendEmail')
            ->willReturn(true);
        $MockCron->expects($this->exactly(2))
            ->method('updateEmailQueue')
            ->willReturn(true);

        // the test
        $MockCron->run();
    }
}
