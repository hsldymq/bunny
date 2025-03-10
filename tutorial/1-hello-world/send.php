<?php

declare(strict_types=1);

use Bunny\Client;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new Client();
$channel = $client->channel();
$channel->queueDeclare('hello', false, false, false, false);

$channel->publish('Hello World!', [], '', 'hello');
echo ' [x] Sent "Hello World!"' . PHP_EOL;

$channel->close();
$client->disconnect();
