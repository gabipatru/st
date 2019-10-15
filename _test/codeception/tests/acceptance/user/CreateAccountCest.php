<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the Create Account page
 */
class CreateAccountCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the Create Account page
     */
    public function testCreateAccount(AcceptanceTester $I)
    {
        $I->amOnPage('/user/newuser.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('#email');
        $I->seeElement('#username');
        $I->seeElement('#password');
        $I->seeElement('#password2');
        $I->seeElement('#first_name');
        $I->seeElement('#last_name');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting existing username
     */
    public function testCreateAccountDuplicateUsername(AcceptanceTester $I)
    {
        $I->amOnPage('/user/newuser.html');

        // fill in user credentials
        $I->fillField('#email', 'test@test.com');
        $I->fillField('#username', 'admin');
        $I->fillField('#password', 'qwe');
        $I->fillField('#password2', 'qwe');
        $I->fillField('#first_name', 'test');
        $I->fillField('#last_name', 'test');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('div.msg-error');
        $I->see('This username is already taken. Please choose another one', '.msg-error');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting existing email address
     */
    public function testCreateAccountDuplicateEmail(AcceptanceTester $I)
    {
        $I->amOnPage('/user/newuser.html');

        // fill in user credentials
        $I->fillField('#email', 'gabipatru@gmail.com');
        $I->fillField('#username', 'test1');
        $I->fillField('#password', 'qwe');
        $I->fillField('#password2', 'qwe');
        $I->fillField('#first_name', 'test');
        $I->fillField('#last_name', 'test');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('div.msg-error');
        $I->see('A user with that email already exists. Please use another email', '.msg-error');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting different passwords
     */
    public function testCreateAccountDifferentPasswords(AcceptanceTester $I)
    {
        $I->amOnPage('/user/newuser.html');

        // fill in user credentials
        $I->fillField('#email', 'test1@test.com');
        $I->fillField('#username', 'test1');
        $I->fillField('#password', 'qwe');
        $I->fillField('#password2', 'qwe1');
        $I->fillField('#first_name', 'test');
        $I->fillField('#last_name', 'test');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('div.msg-error');
        $I->see('Please fill all the required fields', '.msg-error');
        $I->see('Passwords must be identical', '#password2-error');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting wrong email
     */
    public function testCreateAccountWrongEmail(AcceptanceTester $I)
    {
        $I->amOnPage('/user/newuser.html');

        // fill in user credentials
        $I->fillField('#email', 'test');
        $I->fillField('#username', 'test1');
        $I->fillField('#password', 'qwe');
        $I->fillField('#password2', 'qwe1');
        $I->fillField('#first_name', 'test');
        $I->fillField('#last_name', 'test');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('div.msg-error');
        $I->see('Please fill all the required fields', '.msg-error');
        $I->see('Please enter a valid email address', '#email-error');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting no username
     */
    public function testCreateAccountNoUsername(AcceptanceTester $I)
    {
        $I->amOnPage('/user/newuser.html');

        // fill in user credentials
        $I->fillField('#email', 'test1@test.com');
        $I->fillField('#username', '');
        $I->fillField('#password', 'qwe');
        $I->fillField('#password2', 'qwe1');
        $I->fillField('#first_name', 'test');
        $I->fillField('#last_name', 'test');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('div.msg-error');
        $I->see('Please fill all the required fields', '.msg-error');
        $I->see('Please choose a username, at least 2 characters long', '#username-error');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting no password
     */
    public function testCreateAccountNoPassword(AcceptanceTester $I)
    {
        $I->amOnPage('/user/newuser.html');

        // fill in user credentials
        $I->fillField('#email', 'test1@test.com');
        $I->fillField('#username', 'test-suername');
        $I->fillField('#password', '');
        $I->fillField('#password2', '');
        $I->fillField('#first_name', 'test');
        $I->fillField('#last_name', 'test');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('div.msg-error');
        $I->see('Please fill all the required fields', '.msg-error');
        $I->see('Password is not strong enough', '#password-error');

        $this->testFooter($I);
    }
}
