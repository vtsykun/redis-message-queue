<?php

namespace Okvpn\Bundle\RedisQueueBundle\Extension;

use Okvpn\Bundle\RedisQueueBundle\Client\LaterMessageProducer;
use Oro\Component\MessageQueue\Client\MessageProducerInterface;
use Oro\Component\MessageQueue\Consumption\AbstractExtension;
use Oro\Component\MessageQueue\Consumption\Context;

class FlushMessageExtension extends AbstractExtension
{
    /** @var LaterMessageProducer */
    private $messageProducer;

    /**
     * @param LaterMessageProducer|MessageProducerInterface $messageProducer
     */
    public function __construct(MessageProducerInterface $messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    /**
     * @param Context $context
     */
    public function onPostReceived(Context $context)
    {
        $this->messageProducer->flush();
    }

    /**
     * @param Context $context
     */
    public function onIdle(Context $context)
    {
        $this->messageProducer->flush();
    }

    /**
     * @param Context $context
     */
    public function onInterrupted(Context $context)
    {
        $this->messageProducer->flush();
    }
}
