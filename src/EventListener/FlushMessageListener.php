<?php

namespace Okvpn\Bundle\RedisQueueBundle\EventListener;

use Okvpn\Bundle\RedisQueueBundle\Client\LaterMessageProducer;
use Oro\Component\MessageQueue\Client\MessageProducerInterface;

class FlushMessageListener
{
    /** @var MessageProducerInterface|LaterMessageProducer */
    private $messageProducer;

    /**
     * @param MessageProducerInterface $messageProducer
     */
    public function __construct(MessageProducerInterface $messageProducer = null)
    {
        if ($messageProducer instanceof LaterMessageProducer) {
            $this->messageProducer = $messageProducer;
        }
    }

    public function onTerminate()
    {
        if ($this->messageProducer !== null) {
            $this->messageProducer->flush();
        }
    }
}
