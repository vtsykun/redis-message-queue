<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\Driver\RedisConnection;
use Oro\Component\MessageQueue\Transport\MessageInterface;
use Oro\Component\MessageQueue\Transport\MessageConsumerInterface;
use Oro\Component\MessageQueue\Util\JSON;

class RedisMessageConsumer implements MessageConsumerInterface
{
    /** @var RedisSession */
    protected $session;

    /** @var RedisConnection */
    protected $connection;

    /** @var RedisQueue */
    protected $queue;

    /** @var int microseconds */
    protected $pollingInterval = 1000000;

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
     * @param RedisMessage $message
     */
    public function reject(MessageInterface $message, $requeue = false)
    {
        if ($requeue === true) {
            $connection =  $this->connection->getRedisConnection();
            $name = $this->queue->getQueueName();
            $rMessage = [
                'body' => $message->getBody(),
                'headers' => $message->getHeaders(),
                'properties' => $message->getProperties()
            ];

            if ($message->getDelay() !== null) {
                $connection->zAdd(
                    $this->connection->getSetsName($name),
                    $message->getDelay(),
                    JSON::encode($rMessage)
                );
            } else {
                $connection->lPush(
                    $this->connection->getListName($name, $message->getPriority()),
                    JSON::encode($rMessage)
                );
            }
        }
    }

    /**
     * @return RedisMessage|null
     */
    protected function receiveMessage()
    {
        $message = false;
        $connection =  $this->connection->getRedisConnection();
        $this->processDelay($connection);

        foreach ($this->connection->getPriorityMap() as $priority) {
            $name = $this->connection->getListName($this->queue->getQueueName(), $priority);
            $message = $connection->rPop($name);
            if (false !== $message) {
                break;
            }
        }

        if (false === $message) {
            return null;
        }

        $message = JSON::decode($message);

        return $this->createMessageFromData($message);
    }

    /**
     * @param \Redis $connection
     */
    protected function processDelay(\Redis $connection)
    {
        $currentTime = time();
        $setsName = $this->connection->getSetsName($this->queue->getQueueName());
        $connection->watch($setsName);
        $messages = $connection->zRangeByScore($setsName, 0, $currentTime);
        if ($messages) {
            $connection->multi();
            $connection->zDeleteRangeByScore($setsName, 0, $currentTime);

            foreach ($messages as $rMessage) {
                $message = $this->createMessageFromData(JSON::decode($rMessage));
                $connection->lPush(
                    $this->connection->getListName($this->queue->getQueueName(), $message->getPriority()),
                    $rMessage
                );
            }
            $connection->exec();
        } else {
            $connection->unwatch();
        }
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
