<?php

declare(strict_types=1);

namespace Bunny\Protocol;

use Bunny\Constants;

/**
 * AMQP 'connection.close' (class #10, method #50) frame.
 *
 * THIS CLASS IS GENERATED FROM amqp-rabbitmq-0.9.1.json. **DO NOT EDIT!**
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MethodConnectionCloseFrame extends MethodFrame
{
    public int $replyCode;

    public int $closeClassId;

    public int $closeMethodId;

    public string $replyText = '';

    public function __construct()
    {
        parent::__construct(Constants::CLASS_CONNECTION, Constants::METHOD_CONNECTION_CLOSE);

        $this->channel = Constants::CONNECTION_CHANNEL;
    }
}
