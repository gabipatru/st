<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

class HomepageCest extends AbstractAcceptanceCest
{
    /**
     * Test the homepage
     */
    public function checkHomepage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(HttpCode::OK);        ;
        
        $this->testUpperBar($I);
        
        $this->testLogo($I);
        
        $I->see('EN');
        
        $this->testLinksForGuest($I);
        
        $this->testNavBar($I);
        
        $this->testFooter($I);
    }
}
