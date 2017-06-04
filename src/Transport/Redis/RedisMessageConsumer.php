<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\Driver\RedisConnection;

use Oro\Component\MessageQueue\Transport\MessageInterface;
use Oro\Component\MessageQueue\Transport\MessageConsumerInterface;
use Oro\Component\MessageQueue\Util\JSON;

class RedisMessageConsumer implements MessageConsumerInterface
{
    /** @var RedisSession */
    private $session;

    /** @var RedisConnection */
    private $connection;

    /** @var RedisQueue */
    private $queue;

    /** @var int microseconds */
    private $pollingInterval = 1000000;

    public function __construct(RedisSession $session, RedisQueue $queue)
    {
        $this->session = $session;
        $this->queue = $queue;
        $this->connection = $session->getConnection();
    }

    /**
     * Set polling interval in milliseconds
     *
     * @param int $mSec
     */
    public function setPollingInterval($mSec)
    {
        $this->pollingInterval = $mSec * 1000;
    }

    /**
     * Get polling interval in milliseconds
     *
     * @return int
     */
    public function getPollingInterval()
    {
        return (int) $this->pollingInterval / 1000;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    public function receive($timeout = 0)
    {
        $startAt = microtime(true);

        while (true) {
            $message = $this->receiveMessage();

            if ($message) {
                return $message;
            }

            usleep($this->pollingInterval);

            if ($timeout && (microtime(true) - $startAt) >= $timeout) {
                break;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @return RedisMessage
     */
    public function receiveNoWait()
    {
        return $this->receiveMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledge(MessageInterface $message)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function reject(MessageInterface $message, $requeue = false)
    {
    }

    /**
     * @return RedisMessage|null
     */
    protected function receiveMessage()
    {
        $connection =  $this->connection->getRedisConnection();

        $priority = 4;
        $message = false;
        while ($priority-- && $message === false) {
            $name = $this->connection->getListName($this->queue->getQueueName(), $priority);
            $message = $connection->rPop($name);
        }

        if (false === $message) {
            return null;
        }

        $message = Json::decode($message);

        return $this->createMessageFromData($message);
    }

    /**
     * @param array $redisMessage
     *
     * @return RedisMessage
     */
    protected function createMessageFromData(array $redisMessage)
    {
        $message = $this->session->createMessage();
        $message->setBody($redisMessage['body']);

        if ($redisMessage['headers']) {
            $message->setHeaders($redisMessage['headers']);
        }

        if ($redisMessage['properties']) {
            $message->setProperties($redisMessage['properties']);
        }

        return $message;
    }
}
