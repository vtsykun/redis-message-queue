<?php

namespace Okvpn\Bundle\RedisQueueBundle\DependencyInjection\Compiler;

use Okvpn\Bundle\RedisQueueBundle\Client\RedisDriver;
use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisConnection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RedisQueueFactoryPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oro_message_queue.client.driver_factory')) {
            return;
        }

        $driverFactory = $container->getDefinition('oro_message_queue.client.driver_factory');

        $connectionToDriverMap = $driverFactory->getArgument(0);
        $connectionToDriverMap = array_replace($connectionToDriverMap, [
            RedisConnection::class => RedisDriver::class,
        ]);

        $driverFactory->replaceArgument(0, $connectionToDriverMap);
    }
}
