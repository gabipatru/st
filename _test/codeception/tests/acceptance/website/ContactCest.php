<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the contact page
 */
class ContactCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the contact page
     */
    public function checkContact(AcceptanceTester $I)
    {
        $I->amOnPage('/website/contact.html');
        $I->seeResponseCodeIs(HttpCode::OK);
        
        $this->testUpperBar($I);
        
        $this->testLogo($I);
        
        $I->see('EN');
        
        $this->testLinksForGuest($I);
        
        $this->testNavBar($I);
        
        $I->see('Contact', '.active');
        
        $I->seeElement('#contact-form');
        $I->seeElement('#name');
        $I->seeElement('#subject');
        $I->seeElement('#message');
        
        $this->testFooter($I);
    }
}