<?php

namespace phpTools;

/**
 * Redis相关操作
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

    public function __construct($lockKey, $expireTime, $maxTimes = 50)
    {
        $this->lockKey    = $lockKey;
        $this->expireTime = $expireTime;
        $this->maxTimes   = $maxTimes;
        $this->redis      = new \Redis();
        $this->randomNum  = mt_rand(1, 100000);
    }

    /**
     * 获取分布式锁
     * @return void
     */
    public function getLock()
    {
        $runNumber = 0;
        while (1) {
            $runNumber++;
            $ret = $this->redis->setNx($this->lockKey, time());
            if ($ret) {
                $this->redis->expire($this->lockKey, $this->expireTime);
                break;
            }
            if ($runNumber > $this->maxTimes) {
                break;
            }
            usleep(100000);
        }
    }

    /**
     * 删除分布式锁
     * @return void
     */
    public function delLock()
    {
        $this->redis->del($this->lockKey);
    }

    /**
     * 获取原子性锁（原子性加锁）
     * @return bool
     */
    public function getAotomicLock()
    {
        // SET key value [EX seconds] [PX milliseconds] [NX|XX]
        return $this->redis->executeCommand('SET', [$this->lockKey, $this->randomNum, 'EX', $this->expireTime, 'NX']);
        //return $this->redis->set($this->nxKey, $this->token, ["nx", "ex" => 10]);
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
        return $this->redis->executeCommand('EVAL', [$script, 1, $this->lockKey, $this->randomNum]);
        //return $this->redis->eval($script, [$this->nxKey, $this->token], 1);
    }
}