<?php

declare(strict_types = 1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'queue.bind' (class #50, method #20) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodQueueBindFrame extends MethodFrame
{

    public string $exchange;

    public int $reserved1 = 0;

    public string $queue = '';

    public string $routingKey = '';

    public bool $nowait = false;

    /** @var array<mixed> */
    public array $arguments = [];

    public function __construct()
    {
        parent::__construct(Constants::CLASS_QUEUE, Constants::METHOD_QUEUE_BIND);
    }

}
