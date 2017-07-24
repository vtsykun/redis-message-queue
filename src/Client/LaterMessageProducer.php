<?php

namespace Okvpn\Bundle\RedisQueueBundle\Client;

use Okvpn\Bundle\RedisQueueBundle\Transport\MessageStorage;
use Oro\Component\MessageQueue\Client\MessageProducerInterface;

class LaterMessageProducer implements MessageProducerInterface
{
    /** @var MessageProducerInterface */
    private $messageProducer;

    /** @var MessageStorage */
    private $storage;

    /**
     * @param MessageProducerInterface $messageProducer
     * @param MessageStorage $storage
     */
    public function __construct(MessageProducerInterface $messageProducer, MessageStorage $storage)
    {
        $this->messageProducer = $messageProducer;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function send($topic, $message)
    {
        $this->storage->push($topic, $message);
    }

    public function flush()
    {
        foreach ($this->storage->flushAll() as list($topic, $message)) {
            $this->messageProducer->send($topic, $message);
        }
    }
}
