<?php

declare(strict_types=1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'basic.publish' (class #60, method #40) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodBasicPublishFrame extends MethodFrame
{
    public int $reserved1 = 0;

    public string $exchange = '';

    public string $routingKey = '';

    public bool $mandatory = false;

    public bool $immediate = false;

    public function __construct()
    {
        parent::__construct(Constants::CLASS_BASIC, Constants::METHOD_BASIC_PUBLISH);
    }
}
