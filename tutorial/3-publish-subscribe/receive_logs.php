<?php

declare(strict_types=1);

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new Client();
$channel = $client->channel();

$channel->exchangeDeclare('logs', 'fanout');
$queue = $channel->queueDeclare('', false, false, true, false);
$channel->queueBind('logs', $queue->queue);

echo ' [*] Waiting for logs. To exit press CTRL+C' . PHP_EOL;

$channel->consume(
    static function (Message $message, Channel $channel, Client $client): void {
        echo ' [x] ' . $message->content . PHP_EOL;
    },
    $queue->queue,
    '',
    false,
    true,
);
