<?php

/**
 * Manage data in the Elasticsearch
 */
class ElasticSearch
{
    const CODE_ADD_SUCCESSFUL = 201;
    const CODE_INDEX_BULK_SUCCESSFUL = 200;
    const CODE_DELETE_SUCCESSFUL = 200;

    use Singleton;

    /**
     * Get the Elasticsearch URL where we make the requests
     *
     * @return string
     */
    private function getUrl()
    {
        return 'http://'. ELASTIC_HOST .':'. ELASTIC_PORT;
    }

    /**
     * Add an element to ElasticSearch
     *
     * @param int $id - the id of the item
     * @param array $arrData - the data to be indexed, must be in the form
     *      ['key1' => 'value1', 'key2' => 'value2', etc]
     * @param string $type - the type of the element
     * @param string $index
     *
     * @return bool
     *
     * @throws Exception
     */
    public function AddItem($id, $arrData, $type, $index)
    {
        $json = json_encode($arrData) . "\n";

        $oHTTPClient = new HTTPClient();
        $oHTTPClient->setUrl($this->getUrl() .'/'. $index .'/'. $type . '/' . $id)
            ->setRequestType(HTTPClient::REQUEST_TYPE_PUT_JSON)
            ->setRequestHeaders(['Content-Type' => 'application/json'])
            ->setParams([$json]);

        $oHTTPClient->sendRequest();

        if ($oHTTPClient->getResponseCode() != self::CODE_ADD_SUCCESSFUL) {
            throw new Exception($oHTTPClient->getResponseBody());
        }

        return true;
    }

    /**
     * Edit an element in ElasticSearch
     *
     * @param int $id - the id of the item
     * @param array $arrData - the data to be indexed, must be in the form
     *      ['key1' => 'value1', 'key2' => 'value2', etc]
     * @param string $type - the type of the element
     * @param string $index
     *
     * @return bool
     *
     * @throws Exception
     */
    public function EditItem($id, $arrData, $type, $index)
    {
        $arrData = ['doc' => $arrData];
        $json = json_encode($arrData) . "\n";

        $oHTTPClient = new HTTPClient();
        $oHTTPClient->setUrl($this->getUrl() .'/'. $index .'/'. $type .'/'. $id .'/_update')
            ->setRequestType(HTTPClient::REQUEST_TYPE_POST_JSON)
            ->setRequestHeaders(['Content-Type' => 'application/json'])
            ->setParams([$json]);

        $oHTTPClient->sendRequest();

        if ($oHTTPClient->getResponseCode() != self::CODE_INDEX_BULK_SUCCESSFUL) {
            throw new Exception($oHTTPClient->getResponseBody());
        }

        return true;
    }

    /**
     * Delete an item from ElasticSearch
     *
     * @param int $id - the id of the element in ElasticSearch
     * @param string $index
     *
     * @return bool
     *
     * @throws Exception
     */
    public function DeleteItem($id, $type, $index)
    {
        $oHTTPClient = new HTTPClient();
        $oHTTPClient->setUrl($this->getUrl() .'/'. $index .'/'. $type .'/' . $id)
            ->setRequestType(HTTPClient::REQUEST_TYPE_DELETE);

        $oHTTPClient->sendRequest();

        if ($oHTTPClient->getResponseCode() != self::CODE_DELETE_SUCCESSFUL) {
            // if the delete failed, we must check if it failed because the item was not found
            $arrBody = json_decode($oHTTPClient->getResponseBody(), true);
            if (array_key_exists('result', $arrBody) && $arrBody['result'] == 'not_found') {
                return true;
            }

            throw new Exception($oHTTPClient->getResponseBody());
        }

        return true;
    }

    /**
     * This function will index all data $arrData contains into ElasticSearch
     *
     * @param array $arrData - the data to be indexed, must be in the form
     *      ['id' => ['key1' => 'value1', 'key2' => 'value2', etc]]
     * @param string $type - the type of data that is being indexed
     * @param string $index - the name of the index
     *
     * @return bool
     *
     * @throws Exception
     */
    public function IndexBulk(&$arrData, $type, $index)
    {
        $json = '';
        foreach ($arrData as $key => $item) {
            $json .= json_encode(['index' => ['_id' => $key]]) . "\n";
            $json .= json_encode($item) . "\n";
        }

        $oHTTPClient = new HTTPClient();
        $oHTTPClient->setUrl($this->getUrl() .'/'. $index .'/'. $type .'/_bulk')
            ->setRequestType(HTTPClient::REQUEST_TYPE_POST_JSON)
            ->setRequestHeaders(['Content-Type' => 'application/json'])
            ->setParams([$json]);

        $oHTTPClient->sendRequest();

        if ($oHTTPClient->getResponseCode() != self::CODE_INDEX_BULK_SUCCESSFUL) {
            throw new Exception($oHTTPClient->getResponseBody());
        }

        return true;
    }
}
