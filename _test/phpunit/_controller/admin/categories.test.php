<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ . '/../../AbstractControllerTest.php');

class ControllerAdminCategories extends AbstractControllerTest
{
    /**
     * Test what happens when trying to delete a category and providing an invalid token
     * @group fast
     */
    public function testDeleteInvalidToken()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/delete');
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test what whappens when calling delete category with invalid category id
     * @group fast
     */
    public function testDeleteInvalidCategoryId()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'categroy_id' => '' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Category ID is missing.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a valid category
     * @group slow
     */
    public function testDelete()
    {
        $this->setUpDB([ 'category' ]);

        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'category_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The category was deleted.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to delete a category that does not exist
     * @group slow
     * @depends testDelete
     */
    public function testDeleteCategoryDoesNotExist()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/delete');
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setGET([ 'category_id' => '1' ], $oMockController);

        // the test
        $oMockController->delete();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while deleting from database.', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a category and providing an invalid token
     * @group fast
     */
    public function testEditInvalidToken()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/edit');
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
     * Test what happens when trying to edit a category and providing invalid params
     * @group fast
     */
    public function testEditInvalidParams()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/edit');
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
     * Test what happens when trying to add a category with the same name as an existing category
     * @group slow
     * @depends testDelete
     */
    public function testEditAddDuplicateName()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST([ 'name' => 'Lazer', 'status' => 'online' ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('A category with that name already exists!', array_keys($messages)));
    }

    /**
     * Test what happens when trying to edit a category and ending up with duplicate names
     * @group slow
     * @depends testDelete
     */
    public function testEditDuplicateName()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST([ 'name' => 'Otto Moto', 'status' => 'online' ], $oMockController);
        $this->setGET([ 'category_id' => '2' ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Error while saving to the database', array_keys($messages)));
    }

    /**
     * Test add a category
     * @group slow
     * @depends testDelete
     */
    public function testEditAddCategory()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST([ 'name' => 'Turbo', 'status' => 'online' ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The category was saved.', array_keys($messages)));
    }

    /**
     * Test edit a category
     * @group slow
     * @depends testDelete
     */
    public function testEditCategory()
    {
        // init and mock
        $oMockController = $this->initController('/admin/index.php/admin/categories/edit');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST([ 'name' => 'Turbo1', 'status' => 'online' ], $oMockController);
        $this->setGET([ 'category_id' => 5 ], $oMockController);

        // the test
        $oMockController->edit();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The category was saved.', array_keys($messages)));
    }
}
