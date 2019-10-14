<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the login page
 */
class LoginCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the login page
     */
    public function testLogin(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('#login');
        $I->seeElement('#username');
        $I->seeElement('#password');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting correct data
     */
    public function testLoginWithCorrectData(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login.html');

        // fill in user credentials
        $I->fillField('#username', 'editor');
        $I->fillField('#password', 'qwqwqw');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        //$this->testLinksForAdmin($I);
        $this->testNavBar($I);

        $I->dontSeeElement('div.msg-error');
        $I->dontSee('Incorect username or password');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting incorrect data
     */
    public function testLoginWithIncorrectData(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login.html');

        // fill in user credentials
        $I->fillField('#username', 'editor');
        $I->fillField('#password', 'qwe');

        // submit form
        $I->click('#submit-form');

        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);

        $I->seeElement('div.msg-error');
        $I->see('Incorect username or password', '.msg-error');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting no username
     */
    public function testLoginWithoutUsername(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login.html');

        // fill in user credentials
        $I->fillField('#password', 'qwe');

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
        $I->see('You need a username or email to login', '#username-error');
        $I->dontSee('You need a password to login');

        $this->testFooter($I);
    }

    /**
     * Test the login page by submitting no password
     */
    public function testLoginWithoutPassword(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login.html');

        // fill in user credentials
        $I->fillField('#username', 'qwe');

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
        $I->see('You need a password to login', '#password-error');
        $I->dontSee('You need a username or email to login');

        $this->testFooter($I);
    }
}
