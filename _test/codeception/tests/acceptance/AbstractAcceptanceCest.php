<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

/**
 * Provide a few useful functions for acceptance tests.
 * All acceptance tests should extend this.
 */
class AbstractAcceptanceCest
{
    /**
     * Make sure the upperbar is displayed correctly
     */
    protected function testUpperBar(AcceptanceTester $I)
    {

        $I->seeElement('.contactinfo');
        $I->see('+', '.contactinfo');
    }
    
    /**
     * Make sure the logo is displayed correctly
     */
    protected function testLogo(AcceptanceTester $I)
    {
        $I->seeElement('.logo');
        $I->seeElement('#logo-top');
    }
    
    /**
     * Make sure the links for guest are displayed correctly
     */
    protected function testLinksForGuest(AcceptanceTester $I)
    {
        $I->canSeeElement('ul.navbar-nav');
        $I->see('Login', 'ul.navbar-nav');
        $I->see('Create account', 'ul.navbar-nav');
        $I->dontSee('Admin', 'ul.navbar-nav');
    }

    /**
     * Make sure the links for admin are displayed correctly
     */
    protected function testLinksForAdmin(AcceptanceTester $I)
    {
        $I->canSeeElement('ul.navbar-nav');
        $I->see('Admin', 'ul.navbar-nav');
        $I->see('Logout', 'ul.navbar-nav');
        $I->dontSee('Login', 'ul.navbar-nav');
    }
    
    /**
     * Make sure the navbar is displayed correctly
     */
    protected function testNavBar(AcceptanceTester $I)
    {
        $I->seeElement('div.mainmenu');
        $I->see('Home', 'ul.navbar-nav');
        $I->see('Contact', 'ul.navbar-nav');
    }

    /**
     * Make sure the admin header is displayed correctly
     */
    protected function testAdminHeader(AcceptanceTester $I)
    {
        // upper bar
        $I->seeElement('div#top');
        $I->see('Admin Surprize Turbo', 'h1');
        $I->seeElement('div#top-navigation');
        $I->see('Welcome,', 'div#top-navigation');

        // main menu
        $I->seeElement('div#navigation');

        // test Breadcrumbs
        $I->seeElement('div.small-nav');
    }

    /**
     * Make sure the admin footer is displayed correctly
     */
    protected function testAdminFooter(AcceptanceTester $I)
    {
        $I->seeElement('div#footer');
        $I->seeElement('span.left');
        $I->see('Copyright © 2017 Surprize Turbo. All rights reserved.', 'span');
        $I->seeElement('span.right');
        $I->see('Designed by Sutprize Turbo', 'span.right');
    }

    /**
     * Make sure the footer is properly displayed
     */
    protected function testFooter(AcceptanceTester $I)
    {
        $I->seeElement('.footer-bottom');
        $I->see('Copyright © 2017 Surprize Turbo. All rights reserved.', 'p.pull-left');
        $I->see('Designed by Surprize Turbo', 'p.pull-right');
    }

    /**
     * Perform a login
     *
     * @param AcceptanceTester $I
     */
    protected function login(AcceptanceTester $I)
    {
        $I->amOnPage('/user/login.html');

        // fill in user credentials
        $I->fillField('#username', 'admin');
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
}