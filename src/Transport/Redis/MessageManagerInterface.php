<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Oro\Component\MessageQueue\Transport\MessageInterface;

interface MessageManagerInterface
{
    /**
     * Remove all message from a queue
     *
     * @return bool
     */
    public function flushAll();

    /**
     * Remove message by id from a queue
     *
     * @param string $messageId
     * @return bool
     */
    public function flushItem($messageId);

    /**
     * Find all message
     * @return MessageInterface[]
     */
    public function findAll();
}
