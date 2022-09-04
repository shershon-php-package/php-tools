<?php

namespace PhpTools\Client;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;

class ElasticClient
{
    /**
     * es的配置
     * @var string
     */
    private static $_config;

    /**
     * es的连接池
     * @var object
     */
    private static $_client;

    /**
     * 获取连接es的单例
     *
     * @param $config
     * @return Client
     * @throws Exception
     */
    public static function getInstance($config): Client
    {
        // 首先确认已安装composer包: elasticsearch/elasticsearch

        $instanceKey = md5(serialize($config));
        if (!isset(self::$_client[$instanceKey])) {
            self::$_config = [
                'host' => $config['host'],
                'port' => $config['port'],
            ];
            if (!empty($config)) {
                $config = array_merge(self::$_config, $config);
            } else {
                $config = self::$_config;
            }
            if (!$config['host'] || !$config['port']) {
                throw new Exception('unknown host or port');
            }

            self::$_client[$instanceKey] = ClientBuilder::create()->setHosts(["{$config['host']}:{$config['port']}"])->build();
        }
        return self::$_client[$instanceKey];
    }
}