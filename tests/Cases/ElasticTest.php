<?php

namespace ToolsTest\Cases;

use PhpTools\Client\ElasticClient;
use PhpTools\ElasticTools;
use PHPUnit\Framework\TestCase;

class ElasticTest extends TestCase
{
    public function testGetClient()
    {
        $indexName = 'php-test2';
        $indexType = 'user';
        $elastic   = new ElasticTools($indexName, $indexType);
        print_r($elastic);
    }

    public function testCreateIndex()
    {
        $indexName = 'php-test2';
        $indexType = 'user';
        $elastic   = new ElasticTools($indexName, $indexType);
        var_dump($elastic->createIndex());
    }

    public function testDeleteIndex()
    {
        $indexName = 'php-test2';
        $indexType = 'user';
        $elastic   = new ElasticTools($indexName, $indexType);
        var_dump($elastic->deleteIndex());
    }

    // ::todo
    public function testSetMap()
    {
        $indexName = 'php-test2';
        $indexType = 'user';
        $elastic   = new ElasticTools($indexName, $indexType);
        $map       = [
            'properties' => [
                'id'    => [
                    'type' => 'integer',
                ],
                'name'  => [
                    'type' => 'keyword',
                ],
                'age'   => [
                    'type' => 'integer',
                ],
                'birth' => [
                    'type' => 'date',
                ],
            ]
        ];
        var_dump($elastic->setMapping($map));
    }

    // ::todo
    public function testGetMap()
    {
        $indexName = 'php-test2';
        $indexType = 'user';
        $elastic   = new ElasticTools($indexName, $indexType);
        var_dump($elastic->getMapping());
    }

    // ::todo
    public function testInsertOneData()
    {

    }

    // ::todo
    public function testInsertBatchData()
    {

    }

    // ::todo
    public function testDeleteById()
    {

    }

    // ::todo
    public function testGetById()
    {
        $config  = [
            'host' => '127.0.0.1',
            'port' => '9200',
        ];
        $elastic = ElasticClient::getInstance($config);

        $params   = [
            'index' => 'php-test',
            'id'    => '1'
        ];
        $response = $elastic->get($params);
        print_r($response);
    }
}