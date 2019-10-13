<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ .'/../AbstractControllerTest.php');

class controller_user extends AbstractControllerTest
{
    /**
     * Test what happens when a logged in user tries to log in again
     * @group fast
     */
    public function test_login_logged_user()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/login');
        $oMockController->method('isLoggedIn')->willReturn(true);
        $oMockController->expects($this->once())
            ->method('redirect')
            ->willReturn(true);

        // the test
        $oMockController->login();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('You are already logged in !', $messages);
    }

    /**
     * Test what happens when the login is called with invalid token
     * @group fast
     */
    public function test_login_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/login');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->login();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('The page delay was too long', $messages);
    }

    /**
     * Test what happens when the login is called with invalid params
     * @group fast
     */
    public function test_login_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/login');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        // the test
        $oMockController->login();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Please fill all the required fields', $messages);
    }

    /**
     * Test what happens when trying to log in with a non-activated user
     * @group slow
     */
    public function test_login_inactive_user()
    {
        // init and mock
        define('WEBSITE_SALT', md5('gunpowder'));
        $oMockController = $this->initController('/index.php/user/login');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setPOST(
            [
                'username' => 'test1',
                'password' => 'test1'
            ],
            $oMockController
        );

        // initialize the database
        $this->setUpDB([ 'config', 'email', 'users' ]);

        // initialize configs
        $this->initConfigs();

        // add a user
        $oUser = new \User();
        $oUserData = new \Collection();
        $oUserData->setUserGroupId(1);
        $oUserData->setEmail('test@test.com');
        $oUserData->setUsername('test1');
        $oUserData->setPassword('test1');
        $oUserData->setFirstName('test');
        $oUserData->setLastName('test');
        $oUserData->setStatus(\User::STATUS_NEW);
        $oUserData->setIsAdmin(0);
        $oUser->Add($oUserData);

        // the test
        $oMockController->login();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('You must activate your account before logging in', $messages);
    }

    /**
     * Test what happens when trying to log in with a banned user
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_login_banned_user()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/login');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        $this->setPOST(
            [
                'username' => 'test2',
                'password' => 'test2'
            ],
            $oMockController
        );

        $oUser = new \User();
        $oUserData = new \Collection();
        $oUserData->setUserGroupId(1);
        $oUserData->setEmail('test1@test.com');
        $oUserData->setUsername('test2');
        $oUserData->setPassword('test2');
        $oUserData->setFirstName('test2');
        $oUserData->setLastName('test2');
        $oUserData->setStatus(\User::STATUS_BANNED);
        $oUserData->setIsAdmin(0);
        $oUser->Add($oUserData);

        // the test
        $oMockController->login();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('This account is banned!', $messages);
    }

    /**
     * Test what happens when trying to log in with a incorrect usernamed
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_login_incorrect_username_password()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/login');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        $oUser = new \User();
        $oUserData = new \Collection();
        $oUserData->setUserGroupId(1);
        $oUserData->setEmail("test3@test.com");
        $oUserData->setUsername('test3');
        $oUserData->setPassword('test3');
        $oUserData->setFirstName('test3');
        $oUserData->setLastName('test3');
        $oUserData->setStatus(\User::STATUS_ACTIVE);
        $oUserData->setIsAdmin(0);
        $oUser->Add($oUserData);

        $this->setPOST(
            [
                'username' => 'test3',
                'password' => 'x'
            ],
            $oMockController
        );

        // the test
        $oMockController->login();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Incorect username or password', $messages);
    }

    /**
     * Test what happens when trying to log in with a incorrect password
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_login_incorrect_password()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/login');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        $oUser = new \User();
        $oUserData = new \Collection();
        $oUserData->setUserGroupId(1);
        $oUserData->setEmail("test4@test.com");
        $oUserData->setUsername('test4');
        $oUserData->setPassword('test4');
        $oUserData->setFirstName('test4');
        $oUserData->setLastName('test4');
        $oUserData->setStatus(\User::STATUS_ACTIVE);
        $oUserData->setIsAdmin(0);
        $oUser->Add($oUserData);

        $this->setPOST(
            [
                'username' => 'x',
                'password' => 'test4'
            ],
            $oMockController
        );

        // the test
        $oMockController->login();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Incorect username or password', $messages);
    }

    /**
     * Test what happens when the login is successful
     * @group slow
     * @depends test_login_incorrect_password
     */
    public function testLogin()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/login');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        $oMockController->expects($this->once())
            ->method('redirect')
            ->willReturn(true);

        $this->setPOST(
            [
                'username' => 'test4',
                'password' => 'test4'
            ],
            $oMockController
        );

        // the test
        $oMockController->login();
    }

    /**
     * Test what happens when a new user creation is called with an invalid token
     * @group fast
     */
    public function test_newuser_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/newuser');
        $this->mockIsPost(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->newuser();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('The page delay was too long', $messages);
    }

    /**
     * Test what happens when a logged in user is trying to create a new user
     * @group fast
     */
    public function test_newuser_logged_user()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/newuser');
        $this->mockIsPost(true, $oMockController);
        $this->mockIsLogedIn(true, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);

        // the test
        $oMockController->newuser();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('You are already logged in !', $messages);
    }

    /**
     * Test what happens when the submitted params are not valid
     * @group fast
     */
    public function test_newuser_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/newuser');
        $this->mockIsPost(true, $oMockController);
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(false, $oMockController);

        // the test
        $oMockController->newuser();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Please fill all the required fields', $messages);
    }

    /**
     * Test what happens when trying to add a user with the same username as an
     * existing user
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_newuser_duplicate_username()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/newuser');
        $this->mockIsPost(true, $oMockController);
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'email' => 'test1@test.com',
                'username' => 'test1',
                'password' => 'test1',
                'password2' => 'test1',
                'first_name' => 'test',
                'last_name' => 'test'
            ],
            $oMockController
        );

        // the test
        $oMockController->newuser();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('This username is already taken. Please choose another one', $messages);
    }

    /**
     * Test what happens when trying to add a user with the same email as an
     * existing user
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_newuser_duplicate_email()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/newuser');
        $this->mockIsPost(true, $oMockController);
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'email' => 'test@test.com',
                'username' => 'test_new',
                'password' => 'test1',
                'password2' => 'test1',
                'first_name' => 'test',
                'last_name' => 'test'
            ],
            $oMockController
        );

        // the test
        $oMockController->newuser();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('A user with that email already exists. Please use another email', $messages);
    }

    /**
     * Test what happens when trying to add a user with the correct credentials
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_newuser()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/newuser');
        $this->mockIsPost(true, $oMockController);
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);

        $this->setPOST(
            [
                'email' => 'test_correct@test.com',
                'username' => 'test_correct',
                'password' => 'test1',
                'password2' => 'test1',
                'first_name' => 'test',
                'last_name' => 'test'
            ],
            $oMockController
        );

        // the test
        $oMockController->newuser();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('User added to the database', $messages);
    }

    /**
     * Test what happens when the user confirmation is called without a code
     * @group fast
     */
    public function test_confirm_no_code()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/confirm');

        // the test
        $oMockController->confirm();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Could not find the code. You need the activetion code to activate the account', $messages);
    }

    /**
     * Test what happens when the use confirmation code is not correct
     * @group fast
     */
    public function test_confirm_wrong_code()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/confirm');

        $this->setGET(
            [
                'code' => 'wrong code',
            ],
            $oMockController
        );

        // the test
        $oMockController->confirm();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Activation code is not correct', $messages);
    }

    /**
     * Test what happens when the use confirmation code is correct
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_confirm()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/confirm');

        // add a User to confirm later
        $oUser = new \User();
        $oUserData = new \Collection();
        $oUserData->setUserGroupId(1);
        $oUserData->setEmail("test_confirm@test.com");
        $oUserData->setUsername('test_confirm');
        $oUserData->setPassword('test_confirm');
        $oUserData->setFirstName('test_confirm');
        $oUserData->setLastName('test_confirm');
        $oUserData->setStatus(\User::STATUS_NEW);
        $oUserData->setIsAdmin(0);
        $confirmationCode = $oUser->Add($oUserData);

        $this->setGET(
            [
                'code' => $confirmationCode,
            ],
            $oMockController
        );

        // the test
        $oMockController->confirm();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Your account is now active', $messages);
    }

    /**
     * Test what happens when calling the forgot password controller
     * while loggeed in
     * @group fast
     */
    public function test_forgot_password_logged_in()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/forgot_password');
        $this->mockIsLogedIn(true, $oMockController);
        $oMockController->expects($this->once())
            ->method('redirect')
            ->willReturn(true);

        // the test
        $oMockController->forgot_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('You cannot reset your password if you are logged in', $messages);
    }

    /**
     * Test what happens when calling the forgot password controller
     * without a valid token
     * @group fast
     */
    public function test_forgot_password_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/forgot_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockIsPost(true, $oMockController);

        // the test
        $oMockController->forgot_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('The page delay was too long', $messages);
    }

    /**
     * Test what happens when calling the forgot password controller
     * with invalid params
     * @group fast
     */
    public function test_forgot_passowrd_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/forgot_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(false, $oMockController);
        $this->mockIsPost(true, $oMockController);

        // the test
        $oMockController->forgot_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Please fill all the required fields', $messages);
    }

    /**
     * Test what happens when calling the forgot password controller
     * with a wrong email
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_forgot_password_wrong_email()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/forgot_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockIsPost(true, $oMockController);

        $this->setPOST(
            [
                'email' => 'wrong@test.com',
            ],
            $oMockController
        );

        // the test
        $oMockController->forgot_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('This email does not exist in the database', $messages);
    }

    /**
     * Test what happens when calling the forgot password controller
     * with correct data
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_forgot_password()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/forgot_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockIsPost(true, $oMockController);

        $this->setPOST(
            [
                'email' => 'test@test.com',
            ],
            $oMockController
        );

        // the test
        $oMockController->forgot_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('An email has benn sent to your email address', $messages);
    }

    /**
     * Test what happens when calling the reset password controller
     * while loggeed in
     * @group fast
     */
    public function test_reset_password_logged_in()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/reset_password');
        $this->mockIsLogedIn(true, $oMockController);
        $oMockController->expects($this->once())
            ->method('redirect')
            ->willReturn(true);

        // the test
        $oMockController->reset_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('You cannot reset your password if you are logged in', $messages);
    }

    /**
     * Test what happens when calling the reset password controller
     * without a valid token
     * @group fast
     */
    public function test_reset_password_invalid_token()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/reset_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockIsPost(true, $oMockController);

        // the test
        $oMockController->reset_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('The page delay was too long', $messages);
    }

    /**
     * Test what happens when calling the reset password controller
     * with invalid params
     * @group fast
     */
    public function test_reset_passowrd_invalid_params()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/reset_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(false, $oMockController);
        $this->mockIsPost(true, $oMockController);

        // the test
        $oMockController->reset_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Please fill all the required fields', $messages);
    }

    /**
     * Data provider for test_reset_passowrd_invalid_confirmation_code
     */
    public function provider_reset_passowrd_invalid_confirmation_code()
    {
        return [
            [''],
            ['wrong code']
        ];
    }

    /**
     * Test what happens when calling the reset password controller
     * with invalid confirmation code
     * @group fast
     * @dataProvider provider_reset_passowrd_invalid_confirmation_code
     */
    public function test_reset_passowrd_invalid_confirmation_code($code)
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/reset_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockIsPost(true, $oMockController);

        $this->setREQUEST(
            [
                'code' => $code,
            ],
            $oMockController
        );

        // the test
        $oMockController->reset_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Incorrect code', $messages);
    }

    /**
     * Test the password reset with correct data
     * @group slow
     * @depends test_login_inactive_user
     */
    public function test_reset_passowrd()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/user/reset_password');
        $this->mockIsLogedIn(false, $oMockController);
        $this->mockSecurityCheckToken(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockIsPost(true, $oMockController);

        // add a User to confirm later
        $oUser = new \User();
        $oUserData = new \Collection();
        $oUserData->setUserGroupId(1);
        $oUserData->setEmail("test_reset@test.com");
        $oUserData->setUsername('test_reset');
        $oUserData->setPassword('test_reset');
        $oUserData->setFirstName('test_reset');
        $oUserData->setLastName('test_reset');
        $oUserData->setStatus(\User::STATUS_ACTIVE);
        $oUserData->setIsAdmin(0);
        $confirmationCode = $oUser->Add($oUserData);

        $this->setREQUEST(
            [
                'code' => $confirmationCode,
            ],
            $oMockController
        );

        $this->setPOST(
            [
                'password' => 'test1',
                'password2' => 'test1'
            ],
            $oMockController
        );

        // the test
        $oMockController->reset_password();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertArrayHasKey('Password was reset', $messages);
    }
}
