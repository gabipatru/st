<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../../AbstractControllerTest.php');

class controller_admin_user_groups extends AbstractControllerTest
{
    /**
     * Test what happens when trying to delete a user group and providing an invalid token
     * @group fast
     */
    public function test_delete_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/delete');
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test what happens when calling delete user group with invalid series id
     * @group fast
     */
    public function test_delete_invalid_user_group_id()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'user_group_id' => '' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('User Group ID is missing.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a valid user group
     * @group slow
     */
    public function test_delete()
    {
        $this->setUpDB([ 'users' ]);

        // add a user group
        $UserGroup = new \UserGroup();
        $data = new \Collection();
        $data->setName('Test');
        $data->setDescription('Test');
        $data->setStatus('online');

        $UserGroup->Add($data);

        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'user_group_id' => '3' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The user group was deleted.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a user group that does not exist
     * @group slow
     * @depends test_delete
     */
    public function test_delete_group_does_not_exist()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'user_group_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while deleting from database.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a user group and providing an invalid token
     * @group fast
     */
    public function test_edit_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/edit');
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
     * Test what happens when trying to edit a user group and providing invalid params
     * @group fast
     */
    public function test_edit_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/edit');
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
     * Test what happens when trying to add a user group with the same name as an existing user group
     * @group slow
     * @depends test_delete
     */
    public function test_edit_add_duplicate_name()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'name' => 'Super Admin',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('A user group with that name already exists!', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a user group and ending up with duplicate names
     * @group slow
     * @depends test_delete
     */
    public function test_edit_duplicate_name()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'name' => 'Super Admin',
                'status' => 'online'
            ],
            $oMockController
        );
        $this->setGET([ 'user_group_id' => '2' ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while saving to the database', array_keys($messages)));
    }

    /**
     * Test add a user group
     * @group slow
     * @depends test_delete
     */
    public function test_edit_add_group()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'name' => 'Test User Group',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The user group was saved.', array_keys($messages)));
    }

    /**
     * Test edit a user group
     * @group slow
     * @depends test_delete
     */
    public function test_edit_group()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/user_groups/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'name' => 'Test',
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
        $this->assertTrue(in_array('The user group was saved.', array_keys($messages)));
    }
}
