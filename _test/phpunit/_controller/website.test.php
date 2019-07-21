<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../AbstractControllerTest.php');

class controller_test_website extends AbstractControllerTest
{
    /**
     * Test what happens when contact is called with invalid params
     * @group fast
     */
    public function test_contact_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/website/contact');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(false, $oMockController);

        // the test
        $oMockController->contact();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Please make sure you filled all the required fields', array_keys($messages)));
    }

    /**
     * Test what happens when contact is called with an invalid security token
     * @group fast
     */
    public function test_contact_invalid_security_token()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/website/contact');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->contact();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }
}
