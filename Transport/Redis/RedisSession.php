<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\Driver\RedisConnection;
use Oro\Component\MessageQueue\Transport\DestinationInterface;
use Oro\Component\MessageQueue\Transport\Exception\InvalidDestinationException;
use Oro\Component\MessageQueue\Transport\SessionInterface;

class RedisSession implements SessionInterface
{
    /**
     * @var RedisConnection
     */
    private $connection;

    /**
     * @param RedisConnection $connection
     */
    public function __construct(RedisConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     *
     * @return RedisMessage
     */
    public function createMessage($body = null, array $properties = [], array $headers = [])
    {
        $message = new RedisMessage();
        $message->setBody($body);
        $message->setProperties($properties);
        $message->setHeaders($headers);

        return $message;
    }

    /**
     * {@inheritdoc}
     *
     * @return RedisQueue
     */
    public function createQueue($name)
    {
        return new RedisQueue($name);
    }

    /**
     * {@inheritdoc}
     *
     * @return RedisTopic
     */
    public function createTopic($name)
    {
        return new RedisTopic($name);
    }

    /**
     * {@inheritdoc}
     *
     * @param RedisQueue $destination
     *
     * @return RedisMessageConsumer
     */
    public function createConsumer(DestinationInterface $destination)
    {
        InvalidDestinationException::assertDestinationInstanceOf($destination, RedisQueue::class);

        return new RedisMessageConsumer($this, $destination);
    }

    /**
     * {@inheritdoc}
     *
     * @return RedisMessageProducer
     */
    public function createProducer()
    {
        return new RedisMessageProducer($this->connection);
    }

    /**
     * {@inheritdoc}
     */
    public function declareTopic(DestinationInterface $destination)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function declareQueue(DestinationInterface $destination)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function declareBind(DestinationInterface $source, DestinationInterface $target)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->connection->getRedisConnection()->close();
    }

    /**
     * @return RedisConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
