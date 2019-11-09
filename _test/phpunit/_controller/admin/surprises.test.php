<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ . '/../../AbstractControllerTest.php');

class ControllerAdminSurprises extends AbstractControllerTest
{
    /**
     * Test what happens when trying to delete a surprises and providing an invalid token
     * @group fast
     */
    public function testDeleteInvalidToken()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/delete');
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test what happens when calling delete series with invalid series id
     * @group fast
     */
    public function testDeleteInvalidSurpriseId()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'surprise_id' => '' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Surprise ID is missing.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a valid surprise
     * @group slow
     */
    public function testDelete()
    {
        $this->setUpDB([ 'category', 'series', 'group', 'surprise' ]);

        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'surprise_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The surprise was deleted.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a surprise that does not exist
     * @group slow
     * @depends testDelete
     */
    public function testDeleteSurpriseDoesNotExist()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'surprise_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while deleting from database.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a surprise and providing an invalid token
     * @group fast
     */
    public function testEditInvalidToken()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a surprise and providing invalid params
     * @group fast
     */
    public function testEditInvalidParams()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Please make sure you filled all mandatory values', array_keys($messages)));
    }

    /**
     * Test what happens when trying to add a surprise with the same name as an existing surprise
     * @group slow
     * @depends testDelete
     */
    public function testEditAddDuplicateName()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'group_id' => '1',
                'name' => 'Turbo 2',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('A surprise with that name already exists!', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a surprise and ending up with duplicate names
     * @group slow
     * @depends testDelete
     */
    public function testEditDuplicateName()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'group_id' => '1',
                'name' => 'Turbo 3',
                'status' => 'online'
            ],
            $oMockController
        );
        $this->setGET([ 'surprise_id' => '2' ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while saving to the database', array_keys($messages)));
    }

    /**
     * Test add a surprise
     * @group slow
     * @depends testDelete
     */
    public function testEditAddSurprise()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'group_id' => '1',
                'name' => 'Turbo 1000',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The surprise was saved.', array_keys($messages)));
    }

    /**
     * Test edit a surprise
     * @group slow
     * @depends testDelete
     */
    public function testEditSurprise()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/surprises/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'group_id' => '1',
                'name' => 'Turbo 1001',
                'status' => 'online'
            ],
            $oMockController
        );
        $this->setGET([ 'surprise_id' => 2 ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The surprise was saved.', array_keys($messages)));
    }
}
