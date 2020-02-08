<?php

/*
 * This script will index and re-index data from the DB into ElasticSearch
 */

namespace Cron;

require_once(__DIR__ . '/_config.php');

class IndexDataInElasticsearch extends AbstractCron
{
    public function run()
    {
        $this->indexCategories();
        $this->indexSeries();
    }

    /**
     * This function will reindex all existing categories.
     */
    private function indexCategories()
    {
        $oElastic = \ElasticSearch::getSingleton();

        $oCategory = new \Category();
        $collectionCategories = $oCategory->Get();

        $arr = [];
        foreach ($collectionCategories as $category) {
            $arr[$category->getCategoryId()] = $category->allFieldsByArray($oCategory->getElasticFields());
        }

        try {
            $oElastic->IndexBulk(
                $arr,
                $oCategory->constantElasticSearchType(),
                $oCategory->constantElasticSearchIndex()
            );
        } catch (\Exception $e) {
            $this->displayMsg($e->getMessage());
        }
    }

    /**
     * This function will reindex all existing series
     */
    private function indexSeries()
    {
        $oElastic = \ElasticSearch::getSingleton();

        $oSeries = new \Series();
        $collectionSeries = $oSeries->Get();

        $arr = [];
        foreach ($collectionSeries as $series) {
            $arr[$series->getSeriesId()] = $series->allFieldsByArray($oSeries->getElasticFields());
        }

        try {
            $oElastic->IndexBulk(
                $arr,
                $oSeries->constantElasticSearchType(),
                $oSeries->constantElasticSearchIndex()
            );
        } catch (\Exception $e) {
            $this->displayMsg($e->getMessage());
        }
    }
}
