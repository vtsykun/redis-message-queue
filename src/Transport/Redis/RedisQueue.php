<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Oro\Component\MessageQueue\Transport\QueueInterface;

class RedisQueue implements QueueInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->name;
    }
}
