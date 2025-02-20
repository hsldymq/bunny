#!/usr/bin/env php
<?php

// Usage: bunny-consumer.php <amqp-uri> <queue-name> <max-seconds>


declare(strict_types = 1);

namespace Bunny\Test\App;

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use React\EventLoop\Loop;
use function Bunny\Test\Library\parseAmqpUri;
use function array_shift;
use function pcntl_signal;
use const SIGINT;

require __DIR__ . '/../../vendor/autoload.php';

/**
 * @param array<string,mixed> $args
 */
function app(array $args): void
{
    $connection = parseAmqpUri($args['amqpUri']);

    $client = new Client($connection);

    pcntl_signal(SIGINT, static function () use ($client): void {
        $client->disconnect();
    });

    $client->connect();
    $channel = $client->channel();

    $channel->qos(0, 1);
    $channel->queueDeclare($args['queueName']);
    $channel->consume(static function (Message $message, Channel $channel): void {
        $channel->ack($message);
    });
    Loop::addTimer($args['maxSeconds'], static function () use ($client): void {
        $client->disconnect();
    });
}

$argv_copy = $argv;

array_shift($argv_copy);

$args = [
    'amqpUri' => array_shift($argv_copy),
    'queueName' => array_shift($argv_copy),
    'maxSeconds' => (int) array_shift($argv_copy),
];

app($args);
