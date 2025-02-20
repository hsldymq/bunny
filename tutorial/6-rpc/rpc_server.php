<?php

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;

function fib($n): float|int|array
{
    if ($n == 0) {
        return 0;
    }

    if ($n == 1) {
        return 1;
    }

    return fib($n - 1) + fib($n - 2);
}

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new Client();
$channel = $client->channel();

$channel->queueDeclare('rpc_queue');

echo " [x] Awaiting RPC requests\n";

$channel->consume(
    function (Message $message, Channel $channel, Client $client): void {
        $n = intval($message->content);
        echo " [.] fib(", $n, ")\n";
        $channel->publish(
            (string) fib($n),
            [
                'correlation_id' => $message->getHeader('correlation_id'),
            ],
            '',
            $message->getHeader('reply_to')
        );
        $channel->ack($message);
    },
    'rpc_queue'
);
