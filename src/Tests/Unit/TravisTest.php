<?php

namespace Okvpn\Bundle\RedisQueueBundle\Tests;

class TravisTest extends \PHPUnit_Framework_TestCase
{
    public function testWhatRedisPhpExtensionLoaded()
    {
        self::assertTrue(class_exists('\\Redis'));
    }
}
