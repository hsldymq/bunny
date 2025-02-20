<?php

declare(strict_types = 1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'basic.recover' (class #60, method #110) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodBasicRecoverFrame extends MethodFrame
{

    public bool $requeue = false;

    public function __construct()
    {
        parent::__construct(Constants::CLASS_BASIC, Constants::METHOD_BASIC_RECOVER);
    }

}
