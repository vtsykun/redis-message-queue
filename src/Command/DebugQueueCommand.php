<?php

namespace Okvpn\Bundle\RedisQueueBundle\Command;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisConnection;
use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisMessage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugQueueCommand extends ContainerAwareCommand
{
    const NAME = 'okvpn:debug:redis-message-queue';

    /** @var RedisConnection */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::NAME);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        if ($container->has('oro_message_queue.transport.redis.connection')) {
            $this->connection = $container->get('oro_message_queue.transport.redis.connection');
        }
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
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