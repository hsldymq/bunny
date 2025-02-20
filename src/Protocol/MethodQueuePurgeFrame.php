<?php

declare(strict_types = 1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'queue.purge' (class #50, method #30) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodQueuePurgeFrame extends MethodFrame
{

    public int $reserved1 = 0;

    public string $queue = '';

    public bool $nowait = false;

    public function __construct()
    {
        parent::__construct(Constants::CLASS_QUEUE, Constants::METHOD_QUEUE_PURGE);
    }

}
