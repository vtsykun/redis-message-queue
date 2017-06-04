<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis\Driver;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisSession;
use Oro\Component\MessageQueue\Transport\ConnectionInterface;

class RedisConnection implements ConnectionInterface
{
    /** @var */
    private $config = [];

    /** @var bool */
    private $initialized = false;

    /** @var \Redis */
    private $connection;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $config = array_replace(
            [
                'host' => '127.0.0.1',
                'port' => 6379,
                'timeout' => 0.0,
                'retry_interval' => 0
            ],
            $config
        );

        $this->config = $config;
        $this->connection = new \Redis();
    }

    /**
     * {@inheritdoc}
     */
    public function createSession()
    {
        return new RedisSession($this);
    }

    /**
     * @return \Redis
     */
    public function getRedisConnection()
    {
        // lazy load
        if (false === $this->initialized) {
            $this->initialize();
        }

        return $this->connection;
    }

    public function close()
    {
        if (true === $this->initialized) {
            $this->connection->close();
        }
    }

    /**
     * @param string $queueName
     * @param int $priority
     * @return string
     */
    public function getListName($queueName, $priority = 0)
    {
        return sprintf('%s_%s_%s', $this->config['redisTablePrefix'], $queueName, $priority);
    }

    private function initialize()
    {
        if ($this->initialized === true) {
            return;
        }

        $config = $this->config;
        $this->connection->connect($config['host'], $config['port'], $config['timeout'], $config['retry_interval']);

        $this->initialized = true;
    }
}
