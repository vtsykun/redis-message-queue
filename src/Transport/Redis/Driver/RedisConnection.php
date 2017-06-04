<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis\Driver;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisSession;
use Oro\Component\MessageQueue\Client\MessagePriority;
use Oro\Component\MessageQueue\Transport\ConnectionInterface;
use Snc\RedisBundle\DependencyInjection\Configuration\RedisDsn;

class RedisConnection implements ConnectionInterface
{
    /** @var RedisDsn */
    private $dsn;

    /** @var bool */
    private $initialized = false;

    /** @var \Redis */
    private $connection;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->dsn = new RedisDsn($config['dsn']);
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
        return sprintf('%s.%s', $queueName, $priority);
    }

    /**
     * @return array
     */
    public function getPriorityMap()
    {
        return [
            MessagePriority::VERY_HIGH => 4,
            MessagePriority::HIGH => 3,
            MessagePriority::NORMAL => 2,
            MessagePriority::LOW => 1,
            MessagePriority::VERY_LOW => 0,
        ];
    }

    private function initialize()
    {
        if ($this->initialized === true) {
            return;
        }

        if (null !== $this->dsn->getSocket()) {
            $this->connection->connect($this->dsn->getSocket());
        } else {
            $this->connection->connect(
                $this->dsn->getHost(),
                $this->dsn->getPort()
            );
        }

        if ('' !== $this->dsn->getPassword()) {
            $this->connection->auth($this->dsn->getPassword());
        }

        if (0 !== $this->dsn->getDatabase()) {
            $this->connection->select($this->dsn->getDatabase());
        }

        $this->initialized = true;
    }
}
