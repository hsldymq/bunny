<?php

declare(strict_types = 1);

namespace Bunny\Exception;

use function sprintf;

/**
 * Peer sent frame with invalid method class id.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class InvalidClassException extends ProtocolException
{

    public function __construct(private int $classId)
    {
        parent::__construct(sprintf('Unhandled method frame class \'%d\'', $this->classId));
    }

    public function getClassId(): int
    {
        return $this->classId;
    }

}
