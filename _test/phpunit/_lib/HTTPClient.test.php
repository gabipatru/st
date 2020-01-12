<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\IsType;

require_once(__DIR__ . '/../AbstractTest.php');

/**
 * Test the HTTPClient class
 */
class HTTPClient extends AbstractTest
{
    /**
     * Test the function processResponseHeader
     * @group fast
     */
    public function testProcessResponseHeader()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $result = $this->invokeMethod(
            $oHttpClient,
            'processResponseHeader',
            ["HTTP/1.1 200 OK\nServer: nginx/1.10.3\nContent-Type: text/html"]
        );

        // asserts
        $this->assertInternalType(IsType::TYPE_ARRAY, $result);
        $this->assertEquals('HTTP/1.1 200 OK', $result[0]);
        $this->assertArrayHasKey('Server', $result);
        $this->assertEquals('nginx/1.10.3', $result['Server']);
        $this->assertArrayHasKey('Content-Type', $result);
        $this->assertEquals('text/html', $result['Content-Type']);
    }

    /**
     * Test Request Type setter and getter
     * @group fast
     */
    public function testRequestTypeSetterAndGetterCorrectData()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $return = $oHttpClient->setRequestType(\HTTPClient::REQUEST_TYPE_GET);

        // asserts
        $this->assertInstanceOf(\HTTPClient::class, $return);
        $this->assertEquals(\HTTPClient::REQUEST_TYPE_GET, $oHttpClient->getRequestType());
    }

    /**
     * Test Request Type setter with bad data
     * @group fast
     */
    public function testRequestTypeSetterAndGetterBadData()
    {
        // init
        $oHttpClient = new \HTTPClient();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid request type');

        // the test
        $return = $oHttpClient->setRequestType('bad request');
    }

    /**
     * Test URL setter and getter
     * @group fast
     */
    public function testURLSetterAndGetter()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $return = $oHttpClient->setUrl('test');

        // asserts
        $this->assertInstanceOf(\HTTPClient::class, $return);
        $this->assertEquals('test', $oHttpClient->getUrl());
    }

    /**
     * Test Params setter and getters
     * @group fast
     */
    public function testParamsSetterAndGetterCorrectData()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $return = $oHttpClient->setParams(['key1' => 'value1']);

        // asserts
        $this->assertInstanceOf(\HTTPClient::class, $return);

        $params = $oHttpClient->getParams();
        $this->assertInternalType(IsType::TYPE_ARRAY, $params);
        $this->assertNotNull($oHttpClient->getParamsForQuery());
    }

    /**
     * Test Params setter by passing a non-array as params
     * @group fast
     */
    public function testParamsSetterAndGetterIncorrectData()
    {
        // init
        $oHttpClient = new \HTTPClient();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The Request parameters must be an array');

        // the test
        $return = $oHttpClient->setParams('incorrect data');
    }

    /**
     * Test Params setter by passing an array without a key as params
     * @group fast
     */
    public function testParamsSetterAndGetterIncorrectParams()
    {
        // init
        $oHttpClient = new \HTTPClient();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('All parameters must have valid names as array keys');

        // the test
        $return = $oHttpClient->setParams([
            'key1' => 'value1',
            'value2'
        ]);
    }

    /**
     * Test Response Body setter and getter
     * @group fast
     */
    public function testResponseBodySetterAndGetter()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $return = $oHttpClient->setResponseBody('test');

        // asserts
        $this->assertInstanceOf(\HTTPClient::class, $return);
        $this->assertEquals('test', $oHttpClient->getResponseBody());
    }

    /**
     * Test Response Code setter and getter
     * @group fast
     */
    public function testResponseCodeSetterAndGetter()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $return = $oHttpClient->setResponseCode(200);

        // asserts
        $this->assertInstanceOf(\HTTPClient::class, $return);
        $this->assertEquals(200, $oHttpClient->getResponseCode());
    }

    /**
     * Test Response Header Size setter and getter
     * @group fast
     */
    public function testResponseHeaderSizeSetterAndGetter()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $return = $oHttpClient->setResponseHeaderSize(200);

        // asserts
        $this->assertInstanceOf(\HTTPClient::class, $return);
        $this->assertEquals(200, $oHttpClient->getResponseHeaderSize());
    }

    /**
     * Test Response Header setter and getter
     * @group fast
     */
    public function testResponseHeaderSetterAndGetter()
    {
        // init
        $oHttpClient = new \HTTPClient();

        // the test
        $return = $oHttpClient->setResponseHeader('Content-length: 1000');

        // asserts
        $this->assertInstanceOf(\HTTPClient::class, $return);

        $headers = $oHttpClient->getResponseHeader();
        $this->assertInternalType(IsType::TYPE_ARRAY, $headers);
        $this->assertArrayHasKey('Content-length', $headers);
        $this->assertEquals('1000', $headers['Content-length']);
    }

    /**
     * Make a request to 127.0.0.1 and check the request body
     * @group fast
     */
    public function testGetRequest()
    {
        // init
        $oHttpClient = new \HTTPClient();
        $oHttpClient->setUrl('http://127.0.0.1');

        // the test
        $oHttpClient->sendRequest();

        // asserts
        $this->assertEquals(200, $oHttpClient->getResponseCode());

        $headers = $oHttpClient->getResponseHeader();
        $this->assertInternalType(IsType::TYPE_ARRAY, $headers);
        $this->assertArrayHasKey('Content-Length', $headers);
        $this->assertGreaterThan(0, strlen($oHttpClient->getResponseHeaderSize()));
        $this->assertGreaterThan(10, strlen($oHttpClient->getResponseBody()));
    }
}
