<?php

namespace PhpTools;

use Exception;
use MQ\Model\TopicMessage;
use MQ\MQConsumer;
use MQ\MQProducer;
use MQ\Exception\MessageNotExistException;
use MQ\Exception\AckMessageException;
use PhpTools\Client\RocketmqClient;

class RocketmqTools
{
    /**
     * @var RocketmqClient
     */
    private $_client;

    /**
     * @var MQProducer
     */
    private $_producer;

    /**
     * @var MQConsumer
     */
    private $_consumer;

    public function __construct($topic, $instanceId, $groupId)
    {
        $config          = [
            // 设置HTTP接入域名（此处以公共云生产环境为例）
            'endPoint'  => "******",
            // AccessKey 阿里云身份验证，在阿里云服务器管理控制台创建
            'accessKey' => "******",
            // SecretKey 阿里云身份验证，在阿里云服务器管理控制台创建
            'secretKey' => "******"
        ];
        $this->_client   = RocketmqClient::getInstance($config);
        $this->_producer = $this->_client->getProducer($instanceId, $topic);
        !empty($groupId) && $this->_consumer = $this->_client->getConsumer($instanceId, $topic, $groupId);
    }

    /**
     * 生产普通消息
     *
     * @return void
     */
    public function produceSimpleMsg()
    {
        try {
            for ($i = 1; $i <= 4; $i++) {
                $publishMessage = new TopicMessage(
                    "这是一条简单消息{$i}"// 消息内容
                );
                // 设置属性
                $publishMessage->putProperty("a", $i);
                // 设置消息KEY
                $publishMessage->setMessageKey("MessageKey");
                if ($i % 2 == 0) {
                    // 定时消息, 定时时间为10s后
                    $publishMessage->setStartDeliverTime(time() * 1000 + 10 * 1000);
                }
                $result = $this->_producer->publishMessage($publishMessage);

                print "Send mq message success. msgId is:" . $result->getMessageId() . ", bodyMD5 is:" . $result->getMessageBodyMD5() . "\n";
            }
        } catch (Exception $e) {
            print_r($e->getMessage() . "\n");
        }
    }

    /**
     * 消费消息
     *
     * @return void
     */
    public function consume()
    {
        // 在当前线程循环消费消息，建议是多开个几个线程并发消费消息
        while (True) {
            try {
                // 长轮询消费消息
                // 长轮询表示如果topic没有消息则请求会在服务端挂住3s，3s内如果有消息可以消费则立即返回
                $messages = $this->_consumer->consumeMessage(
                    3, // 一次最多消费3条(最多可设置为16条)
                    3 // 长轮询时间3秒（最多可设置为30秒）
                );
            } catch (Exception $e) {
                if ($e instanceof MessageNotExistException) {
                    // 没有消息可以消费，接着轮询
                    printf("No message, contine long polling!RequestId:%s\n", $e->getRequestId());
                    continue;
                }
                print_r($e->getMessage() . "\n");
                sleep(3);
                continue;
            }
            print "consume finish, messages:\n";
            // 处理业务逻辑
            $receiptHandles = array();
            foreach ($messages as $message) {
                $receiptHandles[] = $message->getReceiptHandle();
                printf(
                    "MessageID:%s TAG:%s BODY:%s \nPublishTime:%d, FirstConsumeTime:%d, \nConsumedTimes:%d, NextConsumeTime:%d,MessageKey:%s\n",
                    $message->getMessageId(),
                    $message->getMessageTag(),
                    $message->getMessageBody(),
                    $message->getPublishTime(),
                    $message->getFirstConsumeTime(),
                    $message->getConsumedTimes(),
                    $message->getNextConsumeTime(),
                    $message->getMessageKey()
                );
                print_r($message->getProperties());
            }
            // $message->getNextConsumeTime()前若不确认消息消费成功，则消息会重复消费
            // 消息句柄有时间戳，同一条消息每次消费拿到的都不一样
            print_r($receiptHandles);
            try {
                $this->_consumer->ackMessage($receiptHandles);
            } catch (Exception $e) {
                if ($e instanceof AckMessageException) {
                    // 某些消息的句柄可能超时了会导致确认不成功
                    printf("Ack Error, RequestId:%s\n", $e->getRequestId());
                    foreach ($e->getAckMessageErrorItems() as $errorItem) {
                        printf(
                            "\tReceiptHandle:%s, ErrorCode:%s, ErrorMsg:%s\n",
                            $errorItem->getReceiptHandle(),
                            $errorItem->getErrorCode(),
                            $errorItem->getErrorCode()
                        );
                    }
                }
            }
            print "ack finish\n";
        }
    }
}