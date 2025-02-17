<?php

declare(strict_types=1);

namespace Bunny;

use Bunny\Protocol\MethodBasicConsumeOkFrame;

/**
 * AMQP channel.
 *
 * - Closely works with underlying client instance.
 * - Manages consumers.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 * @final Will be marked final in a future major release
 */
interface ChannelInterface
{
    /**
     * Returns the channel mode.
     */
    public function getMode(): ChannelMode;

    /**
     * Listener is called whenever 'basic.return' frame is received with arguments (Message $returnedMessage, MethodBasicReturnFrame $frame)
     *
     * @param callable $callback
     * @return $this
     */
    public function addReturnListener(callable $callback);

    /**
     * Removes registered return listener. If the callback is not registered, this is noop.
     *
     * @param callable $callback
     * @return $this
     */
    public function removeReturnListener(callable $callback);

    /**
     * Listener is called whenever 'basic.ack' or 'basic.nack' is received.
     *
     * @param callable $callback
     * @return $this
     */
    public function addAckListener(callable $callback);

    /**
     * Removes registered ack/nack listener. If the callback is not registered, this is noop.
     *
     * @param callable $callback
     * @return $this
     */
    public function removeAckListener(callable $callback);

    /**
     * Closes channel.
     *
     * Always returns a promise, because there can be outstanding messages to be processed.
     */
    public function close(int $replyCode = 0, string $replyText = ""): void;

    /**
     * Creates new consumer on channel.
     */
    public function consume(callable $callback, string $queue = "", string $consumerTag = "", bool $noLocal = false, bool $noAck = false, bool $exclusive = false, bool $nowait = false, array $arguments = []): MethodBasicConsumeOkFrame;

    /**
     * Acks given message.
     */
    public function ack(Message $message, bool $multiple = false): bool;

    /**
     * Nacks given message.
     */
    public function nack(Message $message, bool $multiple = false, bool $requeue = true): bool;

    /**
     * Rejects given message.
     */
    public function reject(Message $message, bool $requeue = true): bool;

    /**
     * Synchronously returns message if there is any waiting in the queue.
     */
    public function get(string $queue = "", bool $noAck = false): Message|null;

    /**
     * Published message to given exchange.
     */
    public function publish($body, array $headers = [], string $exchange = '', string $routingKey = '', bool $mandatory = false, bool $immediate = false): bool|int;

    /**
     * Cancels given consumer subscription.
     */
    public function cancel(string $consumerTag, bool $nowait = false): bool|\Bunny\Protocol\MethodBasicCancelOkFrame;

    /**
     * Changes channel to transactional mode. All messages are published to queues only after {@link txCommit()} is called.
     */
    public function txSelect(): \Bunny\Protocol\MethodTxSelectOkFrame;

    /**
     * Commit transaction.
     */
    public function txCommit(): \Bunny\Protocol\MethodTxCommitOkFrame;

    /**
     * Rollback transaction.
     */
    public function txRollback(): \Bunny\Protocol\MethodTxRollbackOkFrame;

    /**
     * Changes channel to confirm mode. Broker then asynchronously sends 'basic.ack's for published messages.
     */
    public function confirmSelect(?callable $callback = null, bool $nowait = false): \Bunny\Protocol\MethodConfirmSelectOkFrame;

    /**
     * Calls basic.qos AMQP method.
     */
    public function qos(int $prefetchSize = 0, int $prefetchCount = 0, bool $global = false): bool|\Bunny\Protocol\MethodBasicQosOkFrame;

    /**
     * Calls queue.declare AMQP method.
     */
    public function queueDeclare(string $queue = '', bool $passive = false, bool $durable = false, bool $exclusive = false, bool $autoDelete = false, bool $nowait = false, array $arguments = []): bool|\Bunny\Protocol\MethodQueueDeclareOkFrame;

    /**
     * Calls queue.bind AMQP method.
     */
    public function queueBind(string $exchange, string $queue = '', string $routingKey = '', bool $nowait = false, array $arguments = []): bool|\Bunny\Protocol\MethodQueueBindOkFrame;

    /**
     * Calls queue.purge AMQP method.
     */
    public function queuePurge(string $queue = '', bool $nowait = false): bool|\Bunny\Protocol\MethodQueuePurgeOkFrame;

    /**
     * Calls queue.delete AMQP method.
     */
    public function queueDelete(string $queue = '', bool $ifUnused = false, bool $ifEmpty = false, bool $nowait = false): bool|\Bunny\Protocol\MethodQueueDeleteOkFrame;

    /**
     * Calls queue.unbind AMQP method.
     */
    public function queueUnbind(string $exchange, string $queue = '', string $routingKey = '', array $arguments = []): bool|\Bunny\Protocol\MethodQueueUnbindOkFrame;

}

