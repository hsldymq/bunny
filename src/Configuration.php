<?php

declare(strict_types=1);

namespace Bunny;

use Closure;
use React\Socket\Connector;
use React\Socket\ConnectorInterface;
use SensitiveParameter;
use function count;
use function sprintf;

final class Configuration
{
    public readonly string $uri;
    public readonly ConnectorInterface $connector;

    public function __construct(
        string $host = Defaults::HOST,
        int $port = Defaults::PORT,
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
        ?ConnectorInterface $connector = null,
    ) {
        $streamScheme = 'tcp';
        if (count($tls) > 0) {
            $streamScheme = 'tls';
        }

        $this->uri = sprintf('%s://%s:%s', $streamScheme, $host, $port);

        $this->connector = $connector ?? new Connector([
            'timeout' => $timeout,
            'tls' => $tls,
        ]);
    }
}
