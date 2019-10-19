<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the Surprises Admin pages (List, Add, Edit)
 */
class AdminSurprisesCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the List Surprises page
     */
    public function testListSurprises(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/surprises/list_surprises.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#sidebar');
        $I->see('Management', 'h2');
        $I->see('Add New Surprise', 'div.box-content');

        $I->seeElement('div#content');
        $I->see('Surprises list', 'h2.left');

        $I->seeElement('div.table');

        $this->testAdminFooter($I);
    }

    /**
     * Check if all elements are correctly displayed on the Add New Surprise page
     */
    public function testAddNewSurprise(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/surprises/edit.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->see('Add New Surprise', 'h2');

        $I->seeElement('select#group_id');
        $I->seeElement('input#name');
        $I->seeElement('textarea#description');
        $I->seeElement('select#status');

        $this->testAdminFooter($I);
    }

    /**
     * Check if we can Add New Surprise without name
     */
    public function testAddNewSurpriseNoName(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/surprises/edit.html');

        // fill in category data
        $I->selectOption('#group_id', '1');
        $I->fillField('#description', 'test@test.com');
        $I->selectOption('#status', 'online');

        // submit form
        $I->click('#save-surprise');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div.msg-error');
        $I->see('Please make sure you filled all mandatory values', '.msg-error');
        $I->see('Please specify a surprise name', '#name-error');

        $this->testAdminFooter($I);
    }

    /**
     * Check if we can Add New Surprise with same name as another surprise
     */
    public function testAddNewSurpriseDuplicateName(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/surprises/edit.html');

        // fill in category data
        $I->selectOption('#group_id', '1');
        $I->fillField('#name', 'Turbo 1');
        $I->fillField('#description', 'test@test.com');
        $I->selectOption('#status', 'online');

        // submit form
        $I->click('#save-surprise');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div.msg-error');
        $I->see('A surprise with that name already exists!', '.msg-error');

        $this->testAdminFooter($I);
    }
}
