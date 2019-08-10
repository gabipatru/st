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

    /**
     * Test that the language is set correctly when called with no referrer
     * @group fast
     */
    public function test_save_language_no_referrer()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/website/save_language');
        $oMockController->expects($this->once())->method('hrefWebsite')->willReturn(true);
        $oMockController->expects($this->once())->method('setCookie')->willReturn(true);
        $this->setGET(
            [
                'language' => 'en_EN',
            ],
            $oMockController
        );

        // the test
        $oMockController->save_language();
    }

    /**
     * Test if an error is set when trying to set a non-existing language
     * @group fast
     */
    public function test_save_language_incorrect_language()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/website/save_language');
        $this->setGET(
            [
                'language' => 'klingon/KLINGON',
                'referrer' => 'test'
            ],
            $oMockController
        );

        // the test
        $oMockController->save_language();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Could not configure language', array_keys($messages)));
    }
}
