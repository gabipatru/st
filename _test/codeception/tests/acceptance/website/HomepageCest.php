<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the homepage
 */
class HomepageCest extends AbstractAcceptanceCest
{
    /**
     * Test that all elements are displayed on the homepage
     */
    public function checkHomepage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(HttpCode::OK);
        
        $this->testUpperBar($I);
        
        $this->testLogo($I);
        
        $I->see('EN');
        
        $this->testLinksForGuest($I);
        
        $this->testNavBar($I);
        
        $I->see('Home', '.active');
        $I->canSeeNumberOfElements('.col-sm-3', 5);
        $I->canSeeNumberOfElements('.category-link', 4);
        
        $this->testFooter($I);
    }
}
