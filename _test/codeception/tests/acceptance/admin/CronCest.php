<?php

namespace Test;

use AcceptanceTester;
use Codeception\Util\HttpCode;

require_once(__DIR__ . '/../AbstractAcceptanceCest.php');

/**
 * Test the Cron Admin pages (List)
 */
class AdminCronCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the main List Crons page
     */
    public function testListCronMain($I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/cron/list_items.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->see('Scheduled Scripts', 'div#content h2');

        $this->testAdminFooter($I);
    }
}
