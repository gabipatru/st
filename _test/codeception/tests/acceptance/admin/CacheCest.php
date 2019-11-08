<?php

namespace Test;

use \AcceptanceTester;
use \Codeception\Util\HttpCode;

require_once(__DIR__ .'/../AbstractAcceptanceCest.php');

/**
 * Test the Cache Admin pages (List)
 */
class AdminCacheCest extends AbstractAcceptanceCest
{
    /**
     * Check if all elements are correctly displayed on the main List Cache page
     */
    public function testListCacheMain($I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/cache/list_cache.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#sidebar');
        $I->see('Cache Management', 'h2');

        $this->testAdminFooter($I);
    }

    /**
     * Check if stuff is displayed on the Memcache page
     */
    public function testListMemcache($I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/cache/memcached.html');
        $I->seeResponseCodeIs(HttpCode::OK);

        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->seeElement('div.table table');

        $this->testAdminFooter($I);
    }

    /**
     * Try to delete one memcache key
     */
    public function testDeleteOneMemcacheKey($I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/cache/memcached.html');

        // fill in the key to be deleted
        $I->fillField('#memcached_key', 'CONFIG_ALL_DATA');

        // submit form - flush the key
        $I->click('#button-flush-one-memcache-key');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->seeElement('div.table table');

        $I->seeElement('div.msg-ok');
        $I->see('Key CONFIG_ALL_DATA was deleted !', 'div.msg-ok');

        $this->testAdminFooter($I);
    }

    /**
     * Try to delete all memcache keys
     */
    public function testDeleteAllMemcacheKeys($I)
    {
        // login as Admin
        $this->login($I);

        $I->amOnPage('/admin/cache/memcached.html');

        // submit form - flush the key
        $I->click('#button-flush-all-memcache-keys');

        // asserts
        $this->testAdminHeader($I);

        $I->seeElement('div#main');
        $I->seeElement('div#content');
        $I->seeElement('div.table table');

        $I->seeElement('div.msg-ok');
        $I->see('Memcached keys flushed', 'div.msg-ok');

        $this->testAdminFooter($I);
    }
}
