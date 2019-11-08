<?php

namespace Test;

use AcceptanceTester;
use Codeception\Util\HttpCode;

require_once(__DIR__ . '/../AbstractAcceptanceCest.php');

/**
 * Test the Groups Admin pages (List, Add, Edit)
 */
class AdminGroupsCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the List Groups page
     */
    public function testListGroups(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/groups/list_groups.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#sidebar');
        $I->see('Management', 'h2');
        $I->see('Add New Groups', 'div.box-content');

        $I->seeElement('div#content');
        $I->see('Groups list', 'h2.left');

        $I->seeElement('div.table');

        $this->testAdminFooter($I);
    }

    /**
     * Check if all elements are correctly displayed on the Add New Groups page
     */
    public function testAddNewGroups(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/groups/edit.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->see('Add New Groups', 'h2');

        $I->seeElement('select#series_id');
        $I->seeElement('input#name');
        $I->seeElement('textarea#description');
        $I->seeElement('select#status');

        $this->testAdminFooter($I);
    }

    /**
     * Check if we can Add New Group without name page
     */
    public function testAddNewGroupNoName(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/groups/edit.html');

        // fill in category data
        $I->selectOption('#series_id', '1');
        $I->fillField('#description', 'test@test.com');
        $I->selectOption('#status', 'online');

        // submit form
        $I->click('#save-group');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div.msg-error');
        $I->see('Please make sure you filled all mandatory values', '.msg-error');
        $I->see('Please specify a group name', '#name-error');

        $this->testAdminFooter($I);
    }

    /**
     * Check if we can Add New Group with same name as another group
     */
    public function testAddNewGroupDuplicateName(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/groups/edit.html');

        // fill in category data
        $I->selectOption('#series_id', '1');
        $I->fillField('#name', 'Turbo 1 - 50');
        $I->fillField('#description', 'test@test.com');
        $I->selectOption('#status', 'online');

        // submit form
        $I->click('#save-group');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div.msg-error');
        $I->see('A group with that name already exists!', '.msg-error');

        $this->testAdminFooter($I);
    }
}
