<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../../AbstractControllerTest.php');

class categories_admin_series extends AbstractControllerTest
{
    /**
     * Test what happens when trying to delete a series and providing an invalid token
     * @group fast
     */
    public function test_delete_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/delete');
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test what whappens when calling delete series with invalid series id
     * @group fast
     */
    public function test_delete_invalid_series_id()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'series_id' => '' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Series ID is missing.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a valid category
     * @group slow
     */
    public function test_delete()
    {
        $this->setUpDB([ 'category', 'series' ]);

        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'series_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The series was deleted.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a series that does not exist
     * @group slow
     * @depends test_delete
     */
    public function test_delete_category_does_not_exist()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'series_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while deleting from database.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a series and providing an invalid token
     * @group fast
     */
    public function test_edit_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/edit');
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
     * Test what happens when trying to edit a series and providing invalid params
     * @group fast
     */
    public function test_edit_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/edit');
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
     * Test what happens when trying to add a series with the same name as an existing series
     * @group slow
     * @depends test_delete
     */
    public function test_edit_add_duplicate_name()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'category_id' => '1',
                'name' => 'Turbo Classic',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('A series with that name already exists!', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a series and ending up with duplicate names
     * @group slow
     * @depends test_delete
     */
    public function test_edit_duplicate_name()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'category_id' => '1',
                'name' => 'Otto Moto',
                'status' => 'online'
            ],
            $oMockController
        );
        $this->setGET([ 'series_id' => '2' ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while saving to the database', array_keys($messages)));
    }

    /**
     * Test add a series
     * @group slow
     * @depends test_delete
     */
    public function test_edit_add_series()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'category_id' => '1',
                'name' => 'Turbo',
                'status' => 'online'
            ],
            $oMockController
        );

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The series was saved.', array_keys($messages)));
    }

    /**
     * Test edit a series
     * @group slow
     * @depends test_delete
     */
    public function test_edit_series()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/series/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'category_id' => '1',
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
        $this->assertTrue(in_array('The series was saved.', array_keys($messages)));
    }
}
