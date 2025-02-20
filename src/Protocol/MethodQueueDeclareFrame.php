<?php

declare(strict_types = 1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'queue.declare' (class #50, method #10) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodQueueDeclareFrame extends MethodFrame
{

    public int $reserved1 = 0;

    public string $queue = '';

    public bool $passive = false;

    public bool $durable = false;

    public bool $exclusive = false;

    public bool $autoDelete = false;

    public bool $nowait = false;

    /** @var array<mixed> */
    public array $arguments = [];

    public function __construct()
    {
        parent::__construct(Constants::CLASS_QUEUE, Constants::METHOD_QUEUE_DECLARE);
    }

}
