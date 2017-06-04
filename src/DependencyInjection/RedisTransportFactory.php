<?php

namespace Okvpn\Bundle\RedisQueueBundle\DependencyInjection;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisConnection;

use Oro\Component\MessageQueue\DependencyInjection\TransportFactoryInterface;

use Snc\RedisBundle\DependencyInjection\Configuration\RedisDsn;
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
                ->scalarNode('dsn')
                    ->defaultValue('redis://@127.0.0.1:6379/0')
                    ->validate()
                        ->ifTrue(
                            function ($dsn) {
                                $parsed = new RedisDsn($dsn);
                                return !$parsed->isValid();
                            }
                        )
                        ->thenInvalid('The redis DSN %s is invalid.')
                    ->end()
                    ->cannotBeEmpty()
                ->end();
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
