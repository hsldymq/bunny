<?php

declare(strict_types = 1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'access.request' (class #30, method #10) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodAccessRequestFrame extends MethodFrame
{

    public string $realm = '/data';

    public bool $exclusive = false;

    public bool $passive = true;

    public bool $active = true;

    public bool $write = true;

    public bool $read = true;

    public function __construct()
    {
        parent::__construct(Constants::CLASS_ACCESS, Constants::METHOD_ACCESS_REQUEST);
    }

}
