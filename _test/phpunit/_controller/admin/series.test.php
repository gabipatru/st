<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ . '/../../AbstractControllerTest.php');

class ControllerAdminSeries extends AbstractControllerTest
{
    /**
     * Test what happens when trying to delete a series and providing an invalid token
     * @group fast
     */
    public function testDeleteInvalidToken()
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
     * Test what happens when calling delete series with invalid series id
     * @group fast
     */
    public function testDeleteInvalidSeriesId()
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
     * Test what happens when trying to delete a valid series
     * @group slow
     */
    public function testDelete()
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
     * @depends testDelete
     */
    public function testDeleteSeriesDoesNotExist()
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
    public function testEditInvalidToken()
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
    public function testEditInvalidParams()
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
     * @depends testDelete
     */
    public function testEditAddDuplicateName()
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
     * @depends testDelete
     */
    public function testEditDuplicateName()
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
     * @depends testDelete
     */
    public function testEditAddSeries()
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
     * @depends testDelete
     */
    public function testEditSeries()
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
