<?php

/*
 * This class implements the abstract methods of daDataModel
 * so you won't have to use boilerplate code
 * every time you extend that method
 *
 * Also, it keeps data between the DB and ElasticSearch if the app
 * is configured to do so.
 */

class DbData extends dbDataModel 
{
    const ALLOW_DELETE_KEY = '/Website/Database/Delete permitted';

    protected $elasticSearchType = '';
    protected $elasticSearchIndex = '';

    /**
     * Fetch the Elasticsearch type which will be defined in the child classes
     *
     * @return string
     */
    public function constantElasticSearchType()
    {
        return $this->elasticSearchType;
    }

    /**
     * Fetch the Elasticsearch index which will be defined in the child classes
     *
     * @return string
     */
    public function constantElasticSearchIndex()
    {
        return $this->elasticSearchIndex;
    }
    
    protected function onBeforeAdd($oItem)
    {
        return true;
    }

    /**
     * Add data to Elasticsearch if the app is configures to do this
     *
     * @param int $iLastId - the last inserted id
     * @param SetterGetter $oItem - the item that was inserted in the DB
     *
     * @return int - the id of the inserted element
     *
     * @throws Exception
     */
    protected function onAdd($iLastId, $oItem)
    {
        if (! USE_ELASTIC_IN_DB_DATA
                || ! $this->constantElasticSearchType()
                || ! $this->constantElasticSearchIndex()) {
            return $iLastId;
        }

        $elastic = ElasticSearch::getSingleton();
        $arrItem = $oItem->allFieldsByArray($this->getElasticFields());

        $result = $elastic->AddItem(
            $iLastId,
            $arrItem,
            $this->constantElasticSearchType(),
            $this->constantElasticSearchIndex()
        );

        if ($result) {
            return $iLastId;
        }

        return 0;
    }
    
    protected function onBeforeEdit($iId, $oItem) {
        return true;
    }

    /**
     * Edit data in Elasticsearch if the app is configures to do this
     *
     * @param int $iId - the ID of the item being edited
     * @param SetterGetter $oItem - the item being edited
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function onEdit($iId, $oItem)
    {
        if (! USE_ELASTIC_IN_DB_DATA
                || ! $this->constantElasticSearchType()
                || ! $this->constantElasticSearchIndex()) {
            return true;
        }

        $elastic = ElasticSearch::getSingleton();
        $arrItem = $oItem->allFieldsByArray($this->getElasticFields());

        return $elastic->EditItem(
            $iId,
            $arrItem,
            $this->constantElasticSearchType(),
            $this->constantElasticSearchIndex()
        );
    }

    protected function onBeforeDelete($iId)
    {
        return true;
    }

    /**
     * Delete data from Elasticsearch if the app is configures to do this
     *
     * @param int $iId - the id of the element being deleted
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function onDelete($iId)
    {
        if (! USE_ELASTIC_IN_DB_DATA
                || ! $this->constantElasticSearchType()
                || ! $this->constantElasticSearchIndex()) {
            return true;
        }

        $elastic = ElasticSearch::getSingleton();

        return $elastic->DeleteItem(
            $iId,
            $this->constantElasticSearchType(),
            $this->constantElasticSearchIndex()
        );
    }

    protected function onBeforeGet($filters, $options)
    {
        return true;
    }
    
    protected function onGet(Collection $oCollection): bool 
    {
        return true;
    }
    
    protected function onSetStatus($iId)
    {
        return true;
    }
}
