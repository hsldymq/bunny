<?php

declare(strict_types=1);

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new Client();
$channel = $client->channel();

$channel->queueDeclare('hello', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', PHP_EOL;

$channel->consume(
    static function (Message $message, Channel $channel): void {
        echo ' [x] Received ' . $message->content . PHP_EOL;
    },
    'hello',
    '',
    false,
    true,
);
