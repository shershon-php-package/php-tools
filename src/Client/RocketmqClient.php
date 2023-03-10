<?php

namespace PhpTools\Client;

use Exception;
use MQ\MQClient;

class RocketmqClient
{
    /**
     * rocketmq的配置
     * @var string
     */
    private static $_config;

    /**
     * rocketmq的连接池
     * @var object
     */
    private static $_client;

    /**
     * 获取连接rocketmq的单例
     *
     * @param $config
     * @return MQClient
     * @throws Exception
     */
    public static function getInstance($config): MQClient
    {
        // 首先确认已安装composer包: aliyunmq/mq-http-sdk（这里版本是1.0.3）
        $instanceKey = md5(serialize($config));
        if (!isset(self::$_client[$instanceKey])) {
            if (empty($config['endPoint']) || empty($config['accessKey']) || empty($config['secretKey'])) {
                throw new Exception('unknown endPoint/accessKey/secretKey');
            }
            self::$_config               = [
                'endPoint'  => $config['endPoint'],
                'accessKey' => $config['accessKey'],
                'secretKey' => $config['secretKey'],
            ];
            self::$_client[$instanceKey] = new MQClient(
                self::$_config['endPoint'],
                self::$_config['accessKey'],
                self::$_config['secretKey'],
            );
        }
        return self::$_client[$instanceKey];
    }
}