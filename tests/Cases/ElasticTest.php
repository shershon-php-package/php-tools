<?php

namespace ToolsTest\Cases;

use PhpTools\Client\ElasticClient;
use PhpTools\ElasticTools;
use PHPUnit\Framework\TestCase;

class ElasticTest extends TestCase
{
    // 获取es客户端
    public function testGetClient()
    {
        $indexName = 'php-test2';
        $elastic   = new ElasticTools($indexName);
        print_r($elastic);
    }

    // 创建索引
    public function testCreateIndex()
    {
        $indexName = 'php-test2';
        $elastic   = new ElasticTools($indexName);
        var_dump($elastic->createIndex());
    }

    // 删除索引
    public function testDeleteIndex()
    {
        $indexName = 'php-test2';
        $elastic   = new ElasticTools($indexName);
        var_dump($elastic->deleteIndex());
    }

    // 单条插入数据
    public function testInsertOneData()
    {
        $indexName    = 'php-test2';
        $elastic      = new ElasticTools($indexName);
        $data['id']   = 1;
        $data['body'] = ['name' => '张三', 'age' => 20, 'height' => 170];
        var_dump($elastic->insert($data));
    }

    // 批量插入数据
    public function testInsertBatchData()
    {
        $indexName = 'php-test2';
        $elastic   = new ElasticTools($indexName);
        for ($i = 0; $i < 5; $i++) {
            $data['body'][] = [
                'index' => [
                    '_index' => $indexName,
                    '_id'    => ($i + 1),
                ],
            ];
            $data['body'][] = [
                'name'   => 'user' . ($i + 1),
                'age'    => 30,
                'height' => 180,
            ];
        }
        var_dump($elastic->bulk($data));
    }

    // 根据ID删除数据
    public function testDeleteById()
    {
        $indexName = 'php-test2';
        $elastic   = new ElasticTools($indexName);
        var_dump($elastic->deleteById('1'));
    }

    // 根据ID更新数据
    public function testUpdateById()
    {
        $indexName    = 'php-test2';
        $elastic      = new ElasticTools($indexName);
        $data['id']   = 2;
        $data['body'] = [
            'doc' => [
                'age' => '31'
            ]
        ];
        var_dump($elastic->updateById($data));
    }

    // 根据ID查询数据
    public function testGetById()
    {
        $config   = [
            'host' => '127.0.0.1',
            'port' => '9200',
        ];
        $elastic  = ElasticClient::getInstance($config);
        $params   = [
            'index' => 'php-test2',
            'id'    => '2'
        ];
        $response = $elastic->get($params);
        var_dump($response);
    }
}