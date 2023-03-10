<?php

namespace ToolsTest\Cases;

use PhpTools\Client\RedisClient;
use PhpTools\RedisTools;
use PHPUnit\Framework\TestCase;

class RedisTest extends TestCase
{
    public function testGetVal()
    {
        $config = [
            'host'   => '127.0.0.1',
            'db'     => 1,
            'port'   => '6379',
            'prefix' => 'YA:',// key的前缀，下面例子中的key -> YA:test
        ];
        $redis  = RedisClient::getInstance($config);
        $redis->set('test', '112233');
        $this->assertEquals('112233', $redis->get('test'));
    }

    public function testGetLock()
    {
        $redis = new RedisTools('lockKey', 30, 5);
        $redis->connect();
        $this->assertEquals($redis->getLock(), true);
    }

    public function testDelLock()
    {
        $redis = new RedisTools('lockKey', 30, 5);
        $redis->connect();
        $this->assertEquals($redis->delLock(), 1);
    }

    public function testGetAotomicLock()
    {
        $redis = new RedisTools('lockKey2', 30, 5);
        $redis->connect();
        $this->assertEquals($redis->getAotomicLock(), false);
    }

    public function testDelAotomicLock()
    {
        $redis = new RedisTools('lockKey2', 30, 5);
        $redis->connect();
        $this->assertEquals($redis->delAotomicLock(), 0);
    }
}
