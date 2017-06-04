<?php

namespace Okvpn\Bundle\RedisQueueBundle;

use Okvpn\Bundle\RedisQueueBundle\DependencyInjection\Compiler\RedisQueueFactoryPass;
use Okvpn\Bundle\RedisQueueBundle\DependencyInjection\RedisTransportFactory;

use Oro\Bundle\MessageQueueBundle\DependencyInjection\OroMessageQueueExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OkvpnRedisQueueBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RedisQueueFactoryPass());

        /** @var OroMessageQueueExtension $extension */
        $extension = $container->getExtension('oro_message_queue');
        $extension->addTransportFactory(new RedisTransportFactory());
    }
}
