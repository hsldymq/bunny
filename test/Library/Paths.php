<?php

declare(strict_types=1);

namespace Bunny\Test\Library;

use function dirname;

final class Paths
{
    public static function getTestsRootPath(): string
    {
        return dirname(__DIR__);
    }
}
