<?php

namespace PhpTools;

/**
 * Desc: Redis相关操作
 * Author: Shershon
 * DateTime: 2022/8/16 23:16
 */
class RedisTools
{
    /**
     * @var string 锁的key
     */
    protected $lockKey;

    /**
     * @var int 锁的过期时间
     */
    protected $expireTime;

    /**
     * @var int 最大重试次数
     */
    protected $maxTimes = 2;

    /**
     * @var \Redis redis实例
     */
    protected $redis;

    /**
     * @var int 随机数
     */
    protected $randomNum;

    /**
     * @var array 连接配置
     */
    protected $config;

    public function __construct($lockKey, $expireTime, $maxTimes = 50)
    {
        $this->lockKey    = $lockKey;
        $this->expireTime = $expireTime;
        $this->maxTimes   = $maxTimes;
        $this->redis      = new \Redis();
        $this->randomNum  = mt_rand(1, 100000);
        $this->config     = ['host' => '127.0.0.1', 'port' => 6379];
    }

    /**
     * 连接Redis
     * @return void
     */
    public function connect() {
        $this->redis->connect($this->config['host'], $this->config['port']);
    }

    /**
     * 获取分布式锁
     * @return bool
     */
    public function getLock()
    {
        $runNumber = 0;
        while (1) {
            $runNumber++;
            $ret = $this->redis->setNx($this->lockKey, $this->randomNum);
            if ($ret) {
                $this->redis->expire($this->lockKey, $this->expireTime);
                break;
            }
            if ($runNumber > $this->maxTimes) {
                break;
            }
            usleep(100000);
        }
        return $ret;
    }

    /**
     * 删除分布式锁
     * @return int
     */
    public function delLock()
    {
        return $this->redis->del($this->lockKey);
    }

    /**
     * 获取原子性锁（原子性加锁）
     * @return bool
     */
    public function getAotomicLock()
    {
        // SET key value [EX seconds] [PX milliseconds] [NX|XX]
//        return $this->redis->executeCommand('SET', [$this->lockKey, $this->randomNum, 'EX', $this->expireTime, 'NX']);
        return $this->redis->set($this->lockKey, $this->randomNum, ["nx", "ex" => $this->expireTime]);
    }

    /**
     * 原子性释放锁
     * @return mixed
     */
    public function delAotomicLock()
    {
        $script = '
            if redis.call("get",KEYS[1]) == ARGV[1]
            then
                return redis.call("del",KEYS[1])
            else
                return 0
            end
        ';
        // EVAL script numkeys key [key ...] arg [arg ...]
//        return $this->redis->executeCommand('EVAL', [$script, 1, $this->lockKey, $this->randomNum]);
        return $this->redis->eval($script, [$this->lockKey, $this->randomNum], 1);
    }
}