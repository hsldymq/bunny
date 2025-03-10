<?php

declare(strict_types=1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'exchange.bind' (class #40, method #30) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodExchangeBindFrame extends MethodFrame
{
    public string $destination;

    public string $source;

    public int $reserved1 = 0;

    public string $routingKey = '';

    public bool $nowait = false;

    /** @var array<mixed> */
    public array $arguments = [];

    public function __construct()
    {
        parent::__construct(Constants::CLASS_EXCHANGE, Constants::METHOD_EXCHANGE_BIND);
    }
}
