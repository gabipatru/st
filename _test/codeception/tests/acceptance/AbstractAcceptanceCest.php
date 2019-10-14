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
     * Make sure the footer is properly displayed
     */
    protected function testFooter(AcceptanceTester $I)
    {
        $I->seeElement('.footer-bottom');
        $I->see('Copyright © 2017 Surprize Turbo. All rights reserved.', 'p.pull-left');
        $I->see('Designed by Surprize Turbo', 'p.pull-right');
    }
}