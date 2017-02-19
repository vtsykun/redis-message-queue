<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

class RedisKey
{
    /** @var string */
    private $messageId;

    /**
     * @param null|string $messageId
     */
    public function __construct($messageId = null)
    {
        if ($messageId === null) {
            $messageId = uniqid();
        }

        $this->messageId = $messageId;
    }

    /**
     * @return string
     */
    public function keyBody()
    {
        return sprintf('%s-body', $this->messageId);
    }

    /**
     * @return string
     */
    public function keyHeaders()
    {
        return sprintf('%s-headers', $this->messageId);
    }

    /**
     * @return string
     */
    public function keyProperties()
    {
        return sprintf('%s-properties', $this->messageId);
    }

    /**
     * @return null|string
     */
    public function keyMessage()
    {
        return $this->messageId;
    }
}
