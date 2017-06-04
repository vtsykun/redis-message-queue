<?php

namespace Okvpn\Bundle\RedisQueueBundle\Test\Transport\Redis;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisTopic;

class RedisTopicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider nameProvider
     *
     * @param string $name
     */
    public function testGetTopicName($name)
    {
        $topic = new RedisTopic($name);

        self::assertSame($name, $topic->getTopicName());
    }

    /**
     * @return \Generator
     */
    public function nameProvider()
    {
        yield [
            'name' => 'test name 1'
        ];

        yield [
            'name' => 'test name 2'
        ];
    }
}
