<?php

namespace Test;

use AcceptanceTester;
use Codeception\Util\HttpCode;

require_once(__DIR__ . '/../AbstractAcceptanceCest.php');

/**
 * Test the contact page
 */
class ContactCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the contact page
     */
    public function testContact(AcceptanceTester $I)
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
    
    /**
     * Test contact page with incorrect email
     */
    public function testContactWithIncorrectEmail(AcceptanceTester $I)
    {
        $I->amOnPage('/website/contact.html');
        
        // fill in some data and a wrong email address
        $I->fillField('#name', 'Gabi');
        $I->fillField('#email', 'Gabi');
        $I->fillField('#subject', 'Test');
        $I->fillField('#message', 'test msg');
        
        // submit form
        $I->click('#submit-form');
        
        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);
        $I->see('Contact', '.active');
        
        $I->seeElement('div.msg-error');
        $I->see('Please make sure you filled all the required fields', '.msg-error');
        $I->see('Please enter a valid email address', '#email-error');
        $I->dontSee('Please fill in your name');
        $I->dontSee('Please specify a subject');
        $I->dontSee('Please write the message we should receive');
        
        $this->testFooter($I);
    }
    
    /**
     * Test the contact page by submitting some data
     */
    public function testContactSubmitError(AcceptanceTester $I)
    {
        $I->amOnPage('/website/contact.html');
        
        // fill in some data except email
        $I->fillField('#name', 'Gabi');
        $I->fillField('#subject', 'Test');
        $I->fillField('#message', 'test msg');
        
        // submit form
        $I->click('#submit-form');
        
        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);
        $I->see('Contact', '.active');
        
        $I->seeElement('div.msg-error');
        $I->see('Please make sure you filled all the required fields', '.msg-error');
        $I->see('Please enter a valid email address');
        $I->dontSee('Please fill in your name');
        $I->dontSee('Please specify a subject');
        $I->dontSee('Please write the message we should receive');
        
        $this->testFooter($I);
    }
    
    /**
     * Test the contact page by submitting no data
     */
    public function testContactSubmitNoData(AcceptanceTester $I)
    {
        $I->amOnPage('/website/contact.html');
        
        // submit form
        $I->click('#submit-form');
        
        // asserts
        $this->testUpperBar($I);
        $this->testLogo($I);
        $I->see('EN');
        $this->testLinksForGuest($I);
        $this->testNavBar($I);
        $I->see('Contact', '.active');
        
        $I->seeElement('div.msg-error');
        $I->see('Please make sure you filled all the required fields', '.msg-error');
        $I->see('Please enter a valid email address');
        $I->See('Please fill in your name');
        $I->See('Please specify a subject');
        $I->See('Please write the message we should receive');
        
        $this->testFooter($I);
    }
}
