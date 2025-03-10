<?php

declare(strict_types=1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'basic.consume' (class #60, method #20) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodBasicConsumeFrame extends MethodFrame
{
    public int $reserved1 = 0;

    public string $queue = '';

    public string $consumerTag = '';

    public bool $noLocal = false;

    public bool $noAck = false;

    public bool $exclusive = false;

    public bool $nowait = false;

    /** @var array<mixed> */
    public array $arguments = [];

    public function __construct()
    {
        parent::__construct(Constants::CLASS_BASIC, Constants::METHOD_BASIC_CONSUME);
    }
}
