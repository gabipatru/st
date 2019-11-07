<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the Config Admin pages (List)
 */
class AdminConfigCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the main List Config page
     */
    public function testListConfigMain($I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/config/list_items.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#sidebar');
        $I->see('Management', 'h2');
        $I->see('Add new Config', 'div.box-content');
        $I->see('Config', 'h2');

        $this->testAdminFooter($I);
    }

    public function testListConfigItem($I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/config/list_items.html?name=Website');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#sidebar');
        $I->see('Management', 'h2');
        $I->see('Add new Config', 'div.box-content');
        $I->see('Config', 'h2');

        $I->seeElement('div#content');
        $I->see('ACL', 'div#content h2');

        $this->testAdminFooter($I);
    }
}
