<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\Driver\RedisConnection as BaseConnection;

use Oro\Component\MessageQueue\Transport\ConnectionInterface;

class RedisConnection implements ConnectionInterface
{
    /** @var BaseConnection */
    private $connection;

    /**
     * @param BaseConnection $connection
     */
    protected function __construct(BaseConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     *
     * @return RedisSession
     */
    public function createSession()
    {
        return new RedisSession($this->connection);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->connection->close();
    }

    /**
     * @param array $config
     * @return static
     */
    public static function createConnection(array $config)
    {
        $redisConnection = new BaseConnection($config);

        return new static($redisConnection);
    }
}
