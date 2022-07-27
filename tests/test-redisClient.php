<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    /*$config = [
        'host' => '127.0.0.1',
        'db' => 1,
        'port' => '6379',
        'prefix' => 'YA:',// key的前缀，下面例子中的key -> YA:test
    ];
    $redis = \phpTools\client\RedisClient::getInstance($config);
    $redis->set('test', '112233');
    var_dump($redis->get('test'));*/

    $redis = new \phpTools\RedisTools('lockKey', 30, 5);
    $redis->connect();
    var_dump($redis->getLock());
    var_dump($redis->delLock());
    var_dump($redis->getAotomicLock());
    var_dump($redis->delAotomicLock());
} catch (Exception $e) {
    var_dump($e->getMessage());
}
