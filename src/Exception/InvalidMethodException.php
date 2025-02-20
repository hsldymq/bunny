<?php

declare(strict_types = 1);

namespace Bunny\Exception;

use function sprintf;

/**
 * Peer sent frame with invalid method id.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class InvalidMethodException extends ProtocolException
{

    public function __construct(private int $classId, private int $methodId)
    {
        parent::__construct(sprintf('Unhandled method frame method \'%d\' in class \'%d\'.', $this->methodId, $this->classId));
    }

    public function getClassId(): int
    {
        return $this->classId;
    }

    public function getMethodId(): int
    {
        return $this->methodId;
    }

}
