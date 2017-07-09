<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Redis;

use Oro\Component\MessageQueue\Transport\MessageInterface;

class RedisMessage implements MessageInterface
{
    /** @var string */
    protected $body;
    
    /** @var array */
    protected $properties;

    /** @var array */
    protected $headers;

    /** @var bool */
    protected $redelivered;

    /** @var int */
    protected $delay;

    public function __construct()
    {
        $this->properties = [];
        $this->headers = [];

        $this->redelivered = false;
    }
    
    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty($name, $default = null)
    {
        return array_key_exists($name, $this->properties) ? $this->properties[$name] : $default;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name, $default = null)
    {
        return array_key_exists($name, $this->headers) ? $this->headers[$name] : $default;
    }

    /**
     * @return boolean
     */
    public function isRedelivered()
    {
        return $this->redelivered;
    }

    /**
     * @param boolean $redelivered
     */
    public function setRedelivered($redelivered)
    {
        $this->redelivered = $redelivered;
    }

    /**
     * {@inheritdoc}
     */
    public function setCorrelationId($correlationId)
    {
        $headers = $this->getHeaders();
        $headers['correlation_id'] = (string) $correlationId;

        $this->setHeaders($headers);
    }

    /**
     * {@inheritdoc}
     */
    public function getCorrelationId()
    {
        return $this->getHeader('correlation_id', '');
    }

    /**
     * {@inheritdoc}
     */
    public function setMessageId($messageId)
    {
        $headers = $this->getHeaders();
        $headers['message_id'] = (string) $messageId;

        $this->setHeaders($headers);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageId()
    {
        return $this->getHeader('message_id', '');
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        return $this->getHeader('timestamp');
    }

    /**
     * {@inheritdoc}
     */
    public function setTimestamp($timestamp)
    {
        $headers = $this->getHeaders();
        $headers['timestamp'] = (int) $timestamp;

        $this->setHeaders($headers);
    }

    /**
     * Gets the number of seconds the message should be removed from the queue without processing
     *
     * @return int|null
     */
    public function getExpire()
    {
        return $this->getHeader('expire');
    }

    /**
     * @param int|null $expire
     */
    public function setExpire($expire)
    {
        if (is_numeric($expire)) {
            $this->headers['expire'] = (int) $expire;
        }
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->headers['priority'] = $priority;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->getHeader('priority', 0);
    }

    /**
     * @return int|null
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param int|null $delay
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }
}
