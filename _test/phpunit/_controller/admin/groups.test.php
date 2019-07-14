<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../../AbstractControllerTest.php');

class categories_admin_groups extends AbstractControllerTest
{
    /**
     * Test what happens when trying to delete a group and providing an invalid token
     * @group fast
     */
    public function test_delete_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/delete');
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test what happens when calling delete group with invalid series id
     * @group fast
     */
    public function test_delete_invalid_group_id()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'group_id' => '' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Group ID is missing.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a valid group
     * @group slow
     */
    public function test_delete()
    {
        $this->setUpDB([ 'category', 'series', 'group' ]);

        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'group_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The group was deleted.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a group that does not exist
     * @group slow
     * @depends test_delete
     */
    public function test_delete_group_does_not_exist()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'group_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while deleting from database.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a group and providing an invalid token
     * @group fast
     */
    public function test_edit_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/edit');
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
     * Test what happens when trying to edit a group and providing invalid params
     * @group fast
     */
    public function test_edit_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/edit');
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
     * Test what happens when trying to add a group with the same name as an existing group
     * @group slow
     * @depends test_delete
     */
    public function test_edit_add_duplicate_name()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'series_id' => '1',
                'name' => 'Turbo 51 - 120',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('A group with that name already exists!', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a group and ending up with duplicate names
     * @group slow
     * @depends test_delete
     */
    public function test_edit_duplicate_name()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'series_id' => '1',
                'name' => 'Turbo 121 - 190',
                'status' => 'online'
            ],
            $oMockController
        );
        $this->setGET([ 'group_id' => '2' ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while saving to the database', array_keys($messages)));
    }

    /**
     * Test add a group
     * @group slow
     * @depends test_delete
     */
    public function test_edit_add_group()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'series_id' => '1',
                'name' => 'Turbo 1 - 50',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The group was saved.', array_keys($messages)));
    }

    /**
     * Test edit a group
     * @group slow
     * @depends test_delete
     */
    public function test_edit_group()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'series_id' => '1',
                'name' => 'Turbo1',
                'status' => 'online'
            ],
            $oMockController
        );
        $this->setGET([ 'series_id' => 2 ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The group was saved.', array_keys($messages)));
    }
}