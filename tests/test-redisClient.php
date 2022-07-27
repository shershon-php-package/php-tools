<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = [
    'host' => '127.0.0.1',
    'db' => 1,
    'port' => '6379',
    'prefix' => 'YA:',// key的前缀，下面例子中的key -> YA:test
];

try {
    $redis = \phpTools\client\RedisClient::getInstance($config);
    var_dump($redis);
    $redis->set('test', '112233');
    var_dump('ok');
} catch (Exception $e) {
    var_dump($e->getMessage());
}
