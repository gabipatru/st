<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ . '/../AbstractControllerTest.php');

class ControllerTestWebsite extends AbstractControllerTest
{
    /**
     * Test that a redirect is done when category id is invalid
     * @group fast
     */
    public function testCategoryInvalidId()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/category');
        $oMockController->expects($this->once())->method('redirect404')->willReturn(true);
        $this->setGET(
            [
                'category_id' => 0
            ],
            $oMockController
        );

        // the test
        $oMockController->category();
    }

    /**
     * Test that a redirect is done when the category id is not found in the DB
     * @group slow
     */
    public function testCategoryNotFoundId()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/category');
        $oMockController->expects($this->once())->method('redirect404')->willReturn(true);
        $this->setGET(
            [
                'category_id' => 99999
            ],
            $oMockController
        );

        $this->setUpDB([ 'category' ]);

        // the test
        $oMockController->category();
    }

    /**
     * Test that a redirect is done when fetching an offline category
     * @group slow
     * @depends testCategoryNotFoundId
     */
    public function testCategoryOffline()
    {
        // add an offline category to DB
        $db = \db::getSingleton();

        $oCategory = new \Category();
        $oCollection = new \Collection();
        $oCollection->setName('Test category');
        $oCollection->setDescription('Test category');
        $oCollection->setStatus('offline');
        $categoryId = $oCategory->Add($oCollection);

        $this->assertGreaterThan(0, $categoryId);

        // init and mock
        $oMockController = $this->initController('/index.php/website/category');
        $oMockController->expects($this->once())->method('redirect404')->willReturn(true);
        $this->setGET(
            [
                'category_id' => $categoryId,
            ],
            $oMockController
        );

        // the test
        $oMockController->category();
    }

    /**
     * Test that a redirect is done when series id is invalid
     * @group fast
     */
    public function testSeriesInvalidId()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/series');
        $oMockController->expects($this->once())->method('redirect404')->willReturn(true);
        $this->setGET(
            [
                'series_id' => 0
            ],
            $oMockController
        );

        // the test
        $oMockController->series();
    }

    /**
     * Test that a redirect is done when series is not found in the DB
     * @group slow
     */
    public function testSeriesNotFoundId()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/series');
        $oMockController->expects($this->once())->method('redirect404')->willReturn(true);
        $this->setGET(
            [
                'series_id' => 9999,
                'series_name' => 'Turbo'
            ],
            $oMockController
        );

        $this->setUpDB([ 'category', 'series', 'group', 'surprise' ]);

        // the test
        $oMockController->series();
    }

    /**
     * Test that a redirect is done when a offline series is accessed
     * @group slow
     * @depends testSeriesNotFoundId
     */
    public function testSeriesNotOnline()
    {
        // add data to DB
        $oSeries = new \Series();
        $oCollection = new \Collection();
        $oCollection->setCategoryId(1);
        $oCollection->setName('Test series offline');
        $oCollection->setDescription('Test series offline');
        $oCollection->setStatus('offline');
        $seriesId = $oSeries->Add($oCollection);

        $this->assertGreaterThan(0, $seriesId);

        // init and mock
        $oMockController = $this->initController('/index.php/website/series');
        $oMockController->expects($this->once())->method('redirect404')->willReturn(true);
        $this->setGET(
            [
                'series_id' => $seriesId,
                'series_name' => 'Test-series-offline'
            ],
            $oMockController
        );

        // the test
        $oMockController->series();
    }

    /**
     * Test that a redirect is done when the URL name does not match the series name
     * @group slow
     * @depends testSeriesNotFoundId
     */
    public function testSeriesNameNotMatched()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/series');
        $oMockController->expects($this->once())->method('redirect404')->willReturn(true);
        $this->setGET(
            [
                'series_id' => 1,
                'series_name' => 'Turbo1'
            ],
            $oMockController
        );

        // the test
        $oMockController->series();
    }

    /**
     * Test that an error is shown when a series without groups is accessed
     * @group slow
     * @depends testSeriesNotFoundId
     */
    public function testSeriesWithoutGroups()
    {
        // add data to DB
        $oSeries = new \Series();
        $oCollection = new \Collection();
        $oCollection->setCategoryId(1);
        $oCollection->setName('Test series');
        $oCollection->setDescription('Test series');
        $oCollection->setStatus('online');
        $seriesId = $oSeries->Add($oCollection);

        $this->assertGreaterThan(0, $seriesId);

        // init and mock
        $oMockController = $this->initController('/index.php/website/series');
        $this->setGET(
            [
                'series_id' => $seriesId,
                'series_name' => 'Test-series'
            ],
            $oMockController
        );

        // the test
        $oMockController->series();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Could not find any groups for the series Test series', array_keys($messages)));
    }

    /**
     * Test that an error is shown when a series without surprises is accessed
     * @group slow
     * @depends testSeriesNotFoundId
     */
    public function testSeriesWithoutSurprises()
    {
        // add series and group to db
        $oSeries = new \Series();
        $oCollection = new \Collection();
        $oCollection->setCategoryId(1);
        $oCollection->setName('Test series2');
        $oCollection->setDescription('Test series2');
        $oCollection->setStatus('online');
        $seriesId = $oSeries->Add($oCollection);

        $this->assertGreaterThan(0, $seriesId);

        $oGroup = new \Group();
        $oCollection = new \Collection();
        $oCollection->setSeriesId($seriesId);
        $oCollection->setName('Test group');
        $oCollection->setDescription('Test group');
        $oCollection->setStatus('online');
        $groupId = $oGroup->Add($oCollection);

        $this->assertGreaterThan(0, $groupId);

        // init and mock
        $oMockController = $this->initController('/index.php/website/series');
        $this->setGET(
            [
                'series_id' => $seriesId,
                'series_name' => 'Test-series2'
            ],
            $oMockController
        );

        // the test
        $oMockController->series();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Could not find any surprises for the series Test series2', array_keys($messages)));
    }

    /**
     * Test what happens when contact is called with invalid params
     * @group fast
     */
    public function testContactInvalidParams()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/contact');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(false, $oMockController);

        // the test
        $oMockController->contact();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Please make sure you filled all the required fields', array_keys($messages)));
    }

    /**
     * Test what happens when contact is called with an invalid security token
     * @group fast
     */
    public function testContactInvalidSecurityToken()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/contact');
        $this->mockIsPost(true, $oMockController);
        $this->mockValidate(true, $oMockController);
        $this->mockSecurityCheckToken(false, $oMockController);

        // the test
        $oMockController->contact();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('The page delay was too long', array_keys($messages)));
    }

    /**
     * Test that the language is set correctly when called with no referrer
     * @group fast
     */
    public function testSaveLanguageNoReferrer()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/save_language');
        $oMockController->expects($this->once())->method('hrefWebsite')->willReturn(true);
        $oMockController->expects($this->once())->method('setCookie')->willReturn(true);
        $this->setGET(
            [
                'language' => 'en_EN',
            ],
            $oMockController
        );

        // the test
        $oMockController->save_language();
    }

    /**
     * Test if an error is set when trying to set a non-existing language
     * @group fast
     */
    public function testSaveLanguageIncorrectLanguage()
    {
        // init and mock
        $oMockController = $this->initController('/index.php/website/save_language');
        $this->setGET(
            [
                'language' => 'klingon/KLINGON',
                'referrer' => 'test'
            ],
            $oMockController
        );

        // the test
        $oMockController->save_language();

        $messages = $this->invokeMethod($oMockController, 'getMessages', []);

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $messages);
        $this->assertTrue(in_array('Could not configure language', array_keys($messages)));
    }
}
