<?php

declare(strict_types=1);

namespace Bunny;

use Closure;
use SensitiveParameter;

final class Configuration
{
    public function __construct(
        public readonly string $host = Defaults::HOST,
        public readonly int $port = Defaults::PORT,
        public readonly string $vhost = Defaults::VHOST,
        #[SensitiveParameter]
        public readonly string $user = Defaults::USER,
        #[SensitiveParameter]
        public readonly string $password = Defaults::PASSWORD,
        public readonly int $timeout = Defaults::TIMEOUT,
        public readonly float $heartbeat = Defaults::HEARTBEAT,
        public readonly ?Closure $heartbeatCallback = Defaults::HEARTBEAT_CALLBACK,
        /**
         * @var array<string, mixed>
         */
        public readonly array $tls = Defaults::TLS,
        /**
         * @var array<string, mixed>
         */
        public readonly array $clientProperties = Defaults::CLIENT_PROPERTIES,
    ) {
    }
}
