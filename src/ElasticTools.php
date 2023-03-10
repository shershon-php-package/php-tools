<?php

namespace PhpTools;

use Exception;
use PhpTools\Client\ElasticClient;

/**
 * Desc: ElasticSearch相关操作
 * Author: Shershon
 * DateTime: 2022/8/16 23:16
 */
class ElasticTools
{
    protected $indexName;

    protected $client;

    public function __construct($indexName)
    {
        $this->indexName = $indexName;
        $config          = [
            'host' => '127.0.0.1',
            'port' => '9200',
        ];
        $this->client    = ElasticClient::getInstance($config);
    }

    /**
     * 初始化索引参数
     *
     * @return array
     */
    public function initParams()
    {
        return [
            'index' => $this->indexName,
        ];
    }

    /**
     * 创建索引
     *
     * @return array
     */
    public function createIndex()
    {
        $initParams['index'] = $this->indexName;
        return $this->client->indices()->create($initParams);
    }

    /**
     * 删除索引
     *
     * @return array
     */
    public function deleteIndex()
    {
        $initParams['index'] = $this->indexName;
        return $this->client->indices()->delete($initParams);
    }

    /**
     * 添加映射
     *
     * @param $map
     * @return array
     */
    public function setMapping($map)
    {
        $initParams         = $this->initParams();
        $initParams['body'] = $map;
        return $this->client->index($initParams);
    }

    /**
     * 获取映射
     *
     * @return array
     */
    public function getMapping()
    {
        $initParams = $this->initParams();
        return $this->client->indices()->getMapping($initParams);
    }

    /**
     * 向索引中插入数据
     *
     * @param $data
     * @return bool
     */
    public function insert($data)
    {
        $params = $this->initParams();
        isset($data['id']) && $params['id'] = $data['id'];
        $params['body'] = $data['body'];
        $res            = $this->client->index($params);
        if (!isset($res['_shards']['successful']) || !$res['_shards']['successful']) {
            return false;
        }
        return true;
    }

    /**
     * 批量插入数据
     *
     * @param $data
     * @return array|callable|false
     */
    public function bulk($data)
    {
        if (empty($data['body'])) return false;
        return $this->client->bulk($data);
    }

    /**
     * 根据唯一id删除
     *
     * @param $id
     * @return bool
     */
    public function deleteById($id)
    {
        $params       = $this->initParams();
        $params['id'] = $id;
        $res          = $this->client->delete($params);
        if (!isset($res['_shards']['successful'])) {
            return false;
        }
        return true;
    }

    /**
     * 根据唯一id更新
     *
     * @param $data
     * @return bool
     */
    public function updateById($data)
    {
        $params = $this->initParams();
        isset($data['id']) && $params['id'] = $data['id'];
        $params['body'] = $data['body'];
        $res            = $this->client->update($params);
        if (!isset($res['_shards']['successful'])) {
            return false;
        }
        return true;
    }

    /**
     * 根据唯一id查询数据
     *
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function searchById($id)
    {
        $params       = $this->initParams();
        $params['id'] = $id;
        return $this->client->get($params);
    }
}