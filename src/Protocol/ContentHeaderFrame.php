<?php

declare(strict_types = 1);

namespace Bunny\Protocol;

use Bunny\Constants;
use DateTime;

/**
 * Content header AMQP frame.
 *
 * Frame's payload wire format:
 *
 *
 *         0          2        4           12      14
 *     ----+----------+--------+-----------+-------+-----------------
 *     ... | class-id | weight | body-size | flags | property-list...
 *     ----+----------+--------+-----------+-------+-----------------
 *            uint16    uint16    uint64     uint16
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class ContentHeaderFrame extends AbstractFrame
{

    public const FLAG_CONTENT_TYPE = 0x8000;

    public const FLAG_CONTENT_ENCODING = 0x4000;

    public const FLAG_HEADERS = 0x2000;

    public const FLAG_DELIVERY_MODE = 0x1000;

    public const FLAG_PRIORITY = 0x0800;

    public const FLAG_CORRELATION_ID = 0x0400;

    public const FLAG_REPLY_TO = 0x0200;

    public const FLAG_EXPIRATION = 0x0100;

    public const FLAG_MESSAGE_ID = 0x0080;

    public const FLAG_TIMESTAMP = 0x0040;

    public const FLAG_TYPE = 0x0020;

    public const FLAG_USER_ID = 0x0010;

    public const FLAG_APP_ID = 0x0008;

    public const FLAG_CLUSTER_ID = 0x0004;

    public int $classId = Constants::CLASS_BASIC;

    public int $weight = 0;

    public int $bodySize;

    public int $flags = 0;

    public string $contentType;

    public string $contentEncoding;

    /**
     * @var array<mixed>
     */
    public array $headers = [];

    public int $deliveryMode;

    public int $priority;

    public string $correlationId;

    public string $replyTo;

    public string $expiration;

    public string $messageId;

    public DateTime $timestamp;

    public string $typeHeader;

    public string $userId;

    public string $appId;

    public string $clusterId;

    public function __construct()
    {
        parent::__construct(Constants::FRAME_HEADER);
    }

    /**
     * Creates frame from array.
     *
     * @param array<string, mixed> $headers
     *
     * @return \Bunny\Protocol\ContentHeaderFrame
     */
    public static function fromArray(array $headers): self
    {
        $instance = new static();

        if (isset($headers['content-type'])) {
            $instance->flags |= self::FLAG_CONTENT_TYPE;
            $instance->contentType = $headers['content-type'];
            unset($headers['content-type']);
        }

        if (isset($headers['content-encoding'])) {
            $instance->flags |= self::FLAG_CONTENT_ENCODING;
            $instance->contentEncoding = $headers['content-encoding'];
            unset($headers['content-encoding']);
        }

        if (isset($headers['delivery-mode'])) {
            $instance->flags |= self::FLAG_DELIVERY_MODE;
            $instance->deliveryMode = $headers['delivery-mode'];
            unset($headers['delivery-mode']);
        }

        if (isset($headers['priority'])) {
            $instance->flags |= self::FLAG_PRIORITY;
            $instance->priority = $headers['priority'];
            unset($headers['priority']);
        }

        if (isset($headers['correlation-id'])) {
            $instance->flags |= self::FLAG_CORRELATION_ID;
            $instance->correlationId = $headers['correlation-id'];
            unset($headers['correlation-id']);
        }

        if (isset($headers['reply-to'])) {
            $instance->flags |= self::FLAG_REPLY_TO;
            $instance->replyTo = $headers['reply-to'];
            unset($headers['reply-to']);
        }

        if (isset($headers['expiration'])) {
            $instance->flags |= self::FLAG_EXPIRATION;
            $instance->expiration = $headers['expiration'];
            unset($headers['expiration']);
        }

        if (isset($headers['message-id'])) {
            $instance->flags |= self::FLAG_MESSAGE_ID;
            $instance->messageId = $headers['message-id'];
            unset($headers['message-id']);
        }

        if (isset($headers['timestamp'])) {
            $instance->flags |= self::FLAG_TIMESTAMP;
            $instance->timestamp = $headers['timestamp'];
            unset($headers['timestamp']);
        }

        if (isset($headers['type'])) {
            $instance->flags |= self::FLAG_TYPE;
            $instance->typeHeader = $headers['type'];
            unset($headers['type']);
        }

        if (isset($headers['user-id'])) {
            $instance->flags |= self::FLAG_USER_ID;
            $instance->userId = $headers['user-id'];
            unset($headers['user-id']);
        }

        if (isset($headers['app-id'])) {
            $instance->flags |= self::FLAG_APP_ID;
            $instance->appId = $headers['app-id'];
            unset($headers['app-id']);
        }

        if (isset($headers['cluster-id'])) {
            $instance->flags |= self::FLAG_CLUSTER_ID;
            $instance->clusterId = $headers['cluster-id'];
            unset($headers['cluster-id']);
        }

        if (!empty($headers)) {
            $instance->flags |= self::FLAG_HEADERS;
            $instance->headers = $headers;
        }

        return $instance;
    }

    /**
     * Inverse function of {@link fromArray()}
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $headers = $this->headers;

        if ($this->contentType !== null) {
            $headers['content-type'] = $this->contentType;
        }

        if ($this->contentEncoding !== null) {
            $headers['content-encoding'] = $this->contentEncoding;
        }

        if ($this->deliveryMode !== null) {
            $headers['delivery-mode'] = $this->deliveryMode;
        }

        if ($this->priority !== null) {
            $headers['priority'] = $this->priority;
        }

        if ($this->correlationId !== null) {
            $headers['correlation-id'] = $this->correlationId;
        }

        if ($this->replyTo !== null) {
            $headers['reply-to'] = $this->replyTo;
        }

        if ($this->expiration !== null) {
            $headers['expiration'] = $this->expiration;
        }

        if ($this->messageId !== null) {
            $headers['message-id'] = $this->messageId;
        }

        if ($this->timestamp !== null) {
            $headers['timestamp'] = $this->timestamp;
        }

        if ($this->typeHeader !== null) {
            $headers['type'] = $this->typeHeader;
        }

        if ($this->userId !== null) {
            $headers['user-id'] = $this->userId;
        }

        if ($this->appId !== null) {
            $headers['app-id'] = $this->appId;
        }

        if ($this->clusterId !== null) {
            $headers['cluster-id'] = $this->clusterId;
        }

        return $headers;
    }

}
