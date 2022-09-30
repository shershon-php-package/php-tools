<?php

namespace ToolsTest\Cases;

use PhpTools\RocketmqTools;
use PHPUnit\Framework\TestCase;

class RocketmqTest extends TestCase
{
    // 生产普通消息
    public function testProduceSimpleMsg()
    {
        // 所属的 Topic
        $topic = "php-simple-topic";
        // Topic所属实例ID，默认实例为空NULL
        $instanceId = "MQ_INST_1050827944341157_BYN8fhkQ";
        $rmq        = new RocketmqTools($topic, $instanceId, '');
        $rmq->produceSimpleMsg();
    }

    // 消费消息
    public function testConsume()
    {
        // 所属的 Topic
        $topic = "php-simple-topic";
        // Topic所属实例ID，默认实例为空NULL
        $instanceId = "MQ_INST_1050827944341157_BYN8fhkQ";
        $groupId    = "GID_PHP_TEST";
        $rmq        = new RocketmqTools($topic, $instanceId, $groupId);
        $rmq->consume();
    }
}