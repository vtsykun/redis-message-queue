<?php

namespace Okvpn\Bundle\RedisQueueBundle\Transport\Semaphore;

interface SemaphoreInterface
{
    /**
     * Acquire a semaphore
     *
     * @param string $key
     * @return bool Return false if a semaphore cannot be immediately acquired.
     */
    public function acquire($key);

    /**
     * Releases the semaphore if it is currently acquired by the calling process, otherwise a warning is generated.
     *
     * @param $key
     * @return mixed
     */
    public function release($key);
}
