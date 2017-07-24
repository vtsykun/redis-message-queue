<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport;

use Oro\Component\MessageQueue\Client\Message;

class MessageStorage
{
    /** @var array */
    private $messages = [];

    /**
     * @param string $topic
     * @param Message $message
     */
    public function push($topic, $message)
    {
        $this->messages[] = [
            'topic' => $topic,
            'message' => $message
        ];
    }

    /**
     * @return array|\Generator
     */
    public function flushAll()
    {
        while ($message = array_shift($this->messages)) {
            yield [$message['topic'], $message['message']];
        }

        return [];
    }
}
