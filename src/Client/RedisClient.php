<?php

namespace PhpTools\Client;

use \Exception;

/**
 * @Description: 连接redis类
 * @Class RedisClient
 * @Package PhpTools\client
 */
class RedisClient
{
    /**
     *
     * 默认0库
     *
     * @var int
     */
    const DEFAULT_DB = 0;

    /**
     * @var object
     */
    private static $_client;

    /**
     * @var array
     */
    private static $_config;

    /**
     * 是否启用长连接
     *
     * @var bool
     */
    private static $_usePersistent = false;

    /**
     * @return bool
     */
    public static function isUsePersistent(): bool
    {
        return self::$_usePersistent;
    }

    /**
     * @param bool $usePersistent
     */
    public static function setUsePersistent(bool $usePersistent): void
    {
        self::$_usePersistent = $usePersistent;
    }

    /**
     * @description: 单例的方式连接redis
     * @param $config
     * @return mixed
     * @throws Exception
     * @autor Shershon
     */
    public static function getInstance($config): \Redis
    {
        if (!extension_loaded('redis')) {
            throw new Exception('redis扩展未安装');
        }
        $instanceKey = md5(serialize($config));
        if (!isset(self::$_client[$instanceKey])) {
            if (empty($config['host']) || empty($config['port'])) {
                throw new Exception('unknown host or port');
            }
            self::$_client[$instanceKey] = $redis = new \Redis();
            self::$_config               = [
                'host'    => $config['host'],
                'port'    => $config['port'],
                'db'      => self::DEFAULT_DB,
                'timeout' => $config['timeout'] ?? 5
            ];
            $connectType                 = 'connect';
            if (self::$_usePersistent) {
                $connectType = 'pconnect';
            }
            //连接
            self::$_client[$instanceKey]->$connectType($config['host'], $config['port'], $config['timeout']);
            //如果有验证
            if (!empty($config['password'])) {
                if ($password = $config['password']) {
                    $redis->auth($password);
                }
            }
            if (isset($config['prefix']) && $config['prefix']) {
                self::$_client[$instanceKey]->setOption(\Redis::OPT_PREFIX,
                    $config['prefix']);
            }
            if (isset($config['db']) && $config['db']) {
                $r = self::$_client[$instanceKey]->select($config['db']);
                if (!$r) {
                    throw new Exception("Redis select db {$config['db']} failure!");
                }
            }
        }
        return self::$_client[$instanceKey];
    }
}