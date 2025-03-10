<?php

declare(strict_types=1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'basic.deliver' (class #60, method #60) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodBasicDeliverFrame extends MethodFrame
{
    public string $consumerTag;

    public int $deliveryTag;

    public string $exchange;

    public string $routingKey;

    public bool $redelivered = false;

    public function __construct()
    {
        parent::__construct(Constants::CLASS_BASIC, Constants::METHOD_BASIC_DELIVER);
    }
}
