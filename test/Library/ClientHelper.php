<?php

declare(strict_types = 1);

namespace Bunny\Test\Library;

use Bunny\Client;
use function array_merge;

final class ClientHelper
{

    /**
     * @param array<string, mixed>|null $options
     */
    public function createClient(?array $options = null): Client
    {
        $options = array_merge($this->getDefaultOptions(), $options ?? []);

        return new Client($options);
    }

    /**
     * @return array<string, mixed>
     */
    public function getDefaultOptions(): array
    {
        $options = [];

        $options = array_merge($options, parseAmqpUri(Environment::getTestRabbitMqConnectionUri()));

        return $options;
    }

}
