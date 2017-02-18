<?php

namespace Okvpn\Bundle\RedisQueueBundle\DependencyInjection;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisConnection;

use Oro\Component\MessageQueue\DependencyInjection\TransportFactoryInterface;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RedisTransportFactory implements TransportFactoryInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name = 'redis')
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('host')->defaultValue('127.0.0.1')->cannotBeEmpty()->end()
                ->scalarNode('port')->defaultValue(6379)->cannotBeEmpty()->end();
    }

    /**
     * {@inheritdoc}
     */
    public function createService(ContainerBuilder $container, array $config)
    {
        $connection = new Definition(RedisConnection::class, [$config]);
        $connection->setFactory([RedisConnection::class, 'createConnection']);
        $connectionId = sprintf('oro_message_queue.transport.%s.connection', $this->getName());
        $container->setDefinition($connectionId, $connection);
        
        return $connectionId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
