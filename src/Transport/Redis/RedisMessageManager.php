<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\Driver\RedisConnection;

class RedisMessageManager implements MessageManagerInterface
{
    /** @var RedisConnection */
    protected $connection;

    /**
     * @param RedisConnection $connection
     */
    public function __construct(RedisConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll()
    {
        $redis = $this->connection->getRedisConnection();
        return $redis->flushAll();
    }

    /**
     * {@inheritdoc}
     */
    public function flushItem($messageId)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
    }
}