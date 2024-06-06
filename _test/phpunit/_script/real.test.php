<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . './../AbstractTest.php';

/**
 * This class will do real tests with the crons - with db connection and db data
 */
class RealCronTests extends AbstractTest
{
    /**
     * Real test for DeleteExpiredConfirmations
     * @group slow
     */
    public function testDeleteExpiredConfirmations()
    {
        // set up DB and add data
        $this->setUpDB([ 'users' ]);
        $oUserConfirmation = new \UserConfirmation();

        $oItem = new \SetterGetter();
        $oItem->setUserId(1);
        $oItem->setCode('123');
        $oItem->setExpiresAt(date('Y-m-d H:i:s', strtotime("-5 hours")));
        $oUserConfirmation->Add($oItem);

        $oItem = new \SetterGetter();
        $oItem->setUserId(1);
        $oItem->setCode('12345678912345678912345678912345');
        $oItem->setExpiresAt(date('Y-m-d H:i:s', strtotime("+5 hours")));
        $oUserConfirmation->Add($oItem);

        // make sure the confirmations are added
        $collection = $oUserConfirmation->Get();
        $this->assertCount(2, $collection);

        // prepare the test
        $db = \db::getSingleton();
        require_once SCRIPT_DIR . '/DeleteExpiredConfirmations.php';
        $oCron = new \Cron\DeleteExpiredConfirmations($db);

        // run the test
        $oCron->run();

        $collection = $oUserConfirmation->Get();

        // asserts
        $this->assertCount(1, $collection);

        $item = $collection->getItem();
        $this->assertEquals('12345678912345678912345678912345', $item->getCode());
        $this->assertEquals(1, $item->getUserId());
    }

    /**
     * Real test for SendQueuedEmail
     * @group slow
     */
    public function testSendQueuedEmail()
    {
        // set up DB and add data
        $this->setUpDB([ 'email' ]);
        $oEmailQueue = new \EmailQueue();

        $oItem = new \SetterGetter();
        $oItem->setToo('test@test.com');
        $oItem->setSubject('Subject');
        $oItem->setBody('Body');
        $oEmailQueue->Add($oItem);

        $oItem = new \SetterGetter();
        $oItem->setToo('test1@test.com');
        $oItem->setSubject('New Subject');
        $oItem->setBody('New Body');
        $oEmailQueue->Add($oItem);

        // prepare the test
        $db = \db::getSingleton();
        require_once SCRIPT_DIR . '/SendQueuedEmail.php';
        $MockCron = $this->getMockBuilder('\Cron\SendQueuedEmail')
            ->setConstructorArgs([$db])
            ->setMethods([ 'displayMsg', 'sendEmail' ])
            ->getMock();
        $MockCron->method('displayMsg')
            ->willReturn(null);

        $i = 0;
        $MockCron->method('sendEmail')
            ->will($this->returnCallback(function () use (&$i) {
                if ($i == 0) {
                    $i++;
                    return false;
                } else {
                    return true;
                }
            }));

        // run the test
        $MockCron->run();

        // get sent and unsent emails
        $collectionNotSent = $oEmailQueue->Get([ 'status' => \EmailQueue::STATUS_NOT_SENT ]);
        $collectionSent = $oEmailQueue->Get([ 'status' => \EmailQueue::STATUS_SENT ]);

        // asserts
        $this->assertCount(1, $collectionNotSent);
        $this->assertCount(1, $collectionSent);
    }
}
