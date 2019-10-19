<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../../AbstractAcceptanceCest.php');

/**
 * Test the Category Admin pages (List, Add, Edit)
 */

class AdminCategoryCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the List Category page
     */
    public function testListCategry(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/categories/list_categories.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#sidebar');
        $I->see('Management', 'h2');
        $I->see('Add New Category', 'div.box-content');

        $I->seeElement('div#content');
        $I->see('Categories list', 'h2.left');

        $I->seeElement('div.table');

        $this->testAdminFooter($I);
    }

    /**
     * Check if all elements are correctly displayed on the Add New Category page
     */
    public function testAddNewCategory(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/categories/edit.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->see('Add new Category', 'h2');

        $I->seeElement('input#name');
        $I->seeElement('textarea#description');
        $I->seeElement('select#status');

        $this->testAdminFooter($I);
    }

    /**
     * Check if we can Add New Category without name page
     */
    public function testAddNewCategoryNoName(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/categories/edit.html');

        // fill in category data
        $I->fillField('#description', 'test@test.com');
        $I->selectOption('#status', 'online');

        // submit form
        $I->click('#save-category');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div.msg-error');
        $I->see('Please make sure you filled all mandatory values', '.msg-error');
        $I->see('Please specify a category name', '#name-error');

        $this->testAdminFooter($I);
    }
}
