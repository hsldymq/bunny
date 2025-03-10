<?php

declare(strict_types=1);

use Bunny\Client;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new Client();
$channel = $client->channel();

$channel->exchangeDeclare('topic_logs', 'topic');

$routingKey = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';
$data = implode(' ', array_slice($argv, 2));
if (empty($data)) {
    $data = 'Hello World!';
}

$channel->publish($data, [], 'topic_logs', $routingKey);
echo ' [x] Sent ' . $routingKey . ':' . $data . PHP_EOL;

$channel->close();
$client->disconnect();
