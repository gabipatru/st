<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the Series Admin pages (List, Add, Edit)
 */
class AdminSeriesCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the List Series page
     */
    public function testListSeries(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/series/list_series.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#sidebar');
        $I->see('Management', 'h2');
        $I->see('Add New Series', 'div.box-content');

        $I->seeElement('div#content');
        $I->see('Series list', 'h2.left');

        $I->seeElement('div.table');

        $this->testAdminFooter($I);
    }

    /**
     * Check if all elements are correctly displayed on the Add New Series page
     */
    public function testAddNewSeries(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/series/edit.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->see('Add new Series', 'h2');

        $I->seeElement('select#category_id');
        $I->seeElement('input#name');
        $I->seeElement('textarea#description');
        $I->seeElement('select#status');

        $this->testAdminFooter($I);
    }

    /**
     * Check if we can Add New Series without name page
     */
    public function testAddNewSeriesNoName(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/series/edit.html');

        // fill in category data
        $I->selectOption('#category_id', '1');
        $I->fillField('#description', 'test@test.com');
        $I->selectOption('#status', 'online');

        // submit form
        $I->click('#save-series');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div.msg-error');
        $I->see('Please make sure you filled all mandatory values', '.msg-error');
        $I->see('Please specify a series name', '#name-error');

        $this->testAdminFooter($I);
    }

    /**
     * Check if we can Add New Series with same name as another series
     */
    public function testAddNewSeriesDuplicateName(AcceptanceTester $I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/series/edit.html');

        // fill in category data
        $I->selectOption('#category_id', '1');
        $I->fillField('#name', 'Turbo Sport');
        $I->fillField('#description', 'test@test.com');
        $I->selectOption('#status', 'online');

        // submit form
        $I->click('#save-series');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div.msg-error');
        $I->see('A series with that name already exists!', '.msg-error');

        $this->testAdminFooter($I);
    }
}
