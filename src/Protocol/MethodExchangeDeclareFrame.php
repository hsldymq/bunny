<?php

declare(strict_types = 1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'exchange.declare' (class #40, method #10) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodExchangeDeclareFrame extends MethodFrame
{

    public string $exchange;

    public int $reserved1 = 0;

    public string $exchangeType = 'direct';

    public bool $passive = false;

    public bool $durable = false;

    public bool $autoDelete = false;

    public bool $internal = false;

    public bool $nowait = false;

    /** @var array<mixed> */
    public array $arguments = [];

    public function __construct()
    {
        parent::__construct(Constants::CLASS_EXCHANGE, Constants::METHOD_EXCHANGE_DECLARE);
    }

}
