<?php

namespace Okvpn\Bundle\RedisQueueBundle\Test\Transport\Redis;

use Okvpn\Bundle\RedisQueueBundle\Transport\Redis\RedisKey;

class RedisKeyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider methodInvokeProvider
     *
     * @param string $name
     * @param string $methodName
     * @param string $expected
     */
    public function testRedisKey($name, $methodName, $expected)
    {
        $redisKey = new RedisKey($name);
        $reflect = new \ReflectionClass($redisKey);
        $value = $reflect->getMethod($methodName)->invoke($redisKey);

        self::assertSame($expected, $value);
    }

    /**
     * @return \Generator
     */
    public function methodInvokeProvider()
    {
        yield 'test keyBody' => [
            'name' => 'test',
            'methodName' => 'keyBody',
            'expected' => 'test-body'
        ];

        yield 'test keyHeaders' => [
            'name' => 'test',
            'methodName' => 'keyHeaders',
            'expected' => 'test-headers'
        ];

        yield 'test keyProperties' => [
            'name' => 'test',
            'methodName' => 'keyProperties',
            'expected' => 'test-properties'
        ];

        yield 'test keyMessage' => [
            'name' => 'test',
            'methodName' => 'keyMessage',
            'expected' => 'test'
        ];
    }
}
