<?php

/**
 * Class HTTPClient enables you to easily make HTTP requests
 * and get all data about them
 */
class HTTPClient
{
    const REQUEST_TIMEOUT = 15;         // seconds

    const REQUEST_TYPE_GET = 'GET';
    const REQUEST_TYPE_POST = 'POST';
    const REQUEST_TYPE_PUT = 'PUT';
    const REQUEST_TYPE_PATCH = 'PATCH';
    const REQUEST_TYPE_OPIONS = 'OPTIONS';
    const REQUEST_TYPE_DELETE = 'DELETE';

    private $requestType;
    private $url;
    private $params;

    private $responseBody;
    private $responseCode;
    private $responseHeaderSize;
    private $responseHeader;

    public function __construct()
    {
        $this->url = null;

        // default request type
        $this->setRequestType(self::REQUEST_TYPE_GET);

        // default params: none
        $this->setParams([]);
    }

    /**
     * The response header is a string. This function processes it and turns it
     * into key-value pairs
     */
    private function processResponseHeader($header)
    {
        if (! $header) {
            return [];
        }

        $splitHeaders = explode("\n", $header);
        $arrHeaders = [];

        foreach ($splitHeaders as $individualHeader) {
            if (strlen($individualHeader) < 2) {
                continue;
            }

            if (strstr($individualHeader, ':') !== false) {
                $splitIndividualHeader = explode(':', $individualHeader);
                $key = trim($splitIndividualHeader[0]);
                $value = trim($splitIndividualHeader[1]);

                $arrHeaders[$key] = $value;
            } else {
                $key = null;
                $value = trim($individualHeader);

                $arrHeaders[] = $value;
            }
        }

        return $arrHeaders;
    }

    #############################################################################
    ## SETTER AND GETTER FUNCTIONS FOR REQUEST TYPE
    #############################################################################
    public function setRequestType($requestType)
    {
        if ($requestType != self::REQUEST_TYPE_GET
                && $requestType != self::REQUEST_TYPE_POST
                && $requestType != self::REQUEST_TYPE_PUT
                && $requestType != self::REQUEST_TYPE_PATCH
                && $requestType != self::REQUEST_TYPE_OPIONS
                && $requestType != self::REQUEST_TYPE_DELETE) {
            throw new Exception('Invalid request type');
        }

        $this->requestType = $requestType;
        return $this;
    }

    public function getRequestType()
    {
        return $this->requestType;
    }

    #############################################################################
    ## SETTER AND GETTER FUNCTIONS FOR URL
    #############################################################################
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    #############################################################################
    ## SETTER AND GETTER FUNCTIONS FOR REQUEST PARAMS
    #############################################################################
    public function setParams($params)
    {
        if (! is_array($params)) {
            throw new Exception('The Request parameters must be an array');
        }

        // make sure all parameters have keys
        foreach ($params as $paramName => $paramValue) {
            if (! $paramName) {
                throw new Exception('All parameters must have valid names as array keys');
            }
        }

        $this->params = $params;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParamsForQuery()
    {
        return http_build_query($this->params);
    }

    #############################################################################
    ## SETTER AND GETTER FUNCTIONS FOR RESPONSE BODY
    #############################################################################
    public function setResponseBody($body)
    {
        $this->responseBody = $body;
        return $this;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }

    #############################################################################
    ## SETTER AND GETTER FUNCTIONS FOR RESPONSE CODE
    #############################################################################
    public function setResponseCode($code)
    {
        $this->responseCode = $code;
        return $this;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }

    #############################################################################
    ## SETTER AND GETTER FUNCTIONS FOR RESPONSE HEADER SIZE
    #############################################################################
    public function setResponseHeaderSize($size)
    {
        $this->responseHeaderSize = $size;
        return $this;
    }

    public function getResponseHeaderSize()
    {
        return $this->responseHeaderSize;
    }

    #############################################################################
    ## SETTER AND GETTER FUNCTIONS FOR RESPONSE HEADER
    #############################################################################
    public function setResponseHeader($header)
    {
        $this->responseHeader = $this->processResponseHeader($header);;
        return $this;
    }

    public function getResponseHeader()
    {
        return $this->responseHeader;
    }

    /**
     * Use CURL to send the HTTP request and retrieve the response
     */
    public function sendRequest()
    {
        // sanity checks
        if (! $this->getRequestType()) {
            throw new Exception('Request type not set');
        }
        if (! $this->getUrl()) {
            throw new Exception('Request URL not set');
        }

        $ch = curl_init();

        // check if the request must be POST
        if ($this->getRequestType() == self::REQUEST_TYPE_POST) {
            curl_setopt($ch, CURLOPT_URL, $this->getUrl());

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getParamsForQuery());
        }

        // if the request is get we must set the params as part of the url
        if ($this->getRequestType() == self::REQUEST_TYPE_GET) {
            curl_setopt($ch, CURLOPT_URL, $this->getUrl(). '?' .$this->getParamsForQuery());
        }

        // Receive server response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // we want to get back the response headers
        curl_setopt($ch, CURLOPT_HEADER, true);

        // set timeout
        curl_setopt($ch, CURLOPT_TIMEOUT,self::REQUEST_TIMEOUT);

        // send request
        $output = curl_exec($ch);

        // save the response code
        $this->setResponseCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));

        // save the response header size
        $this->setResponseHeaderSize(curl_getinfo($ch, CURLINFO_HEADER_SIZE));

        // get response headers and body
        $header = substr($output, 0, $this->getResponseHeaderSize());
        $body = substr($output, $this->getResponseHeaderSize());

        // save the response headers and body
        $this->setResponseHeader($header);
        $this->setResponseBody($body);

        curl_close ($ch);

        return true;
    }
}
