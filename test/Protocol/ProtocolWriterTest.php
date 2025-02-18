<?php
namespace Bunny\Test\Protocol;

use Bunny\Constants;
use Bunny\Protocol\Buffer;
use Bunny\Protocol\ProtocolWriter;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ProtocolWriterTest extends TestCase
{
    public function test_appendFieldValue_canHandleDateTime()
    {
        $buffer = $this->createMock(Buffer::class);
        $protocolWriter = new ProtocolWriter();

        $date = new DateTime();

        $buffer->expects($this->once())
            ->method('appendUint8')
            ->with(Constants::FIELD_TIMESTAMP);
        $buffer->expects($this->once())
            ->method('appendUint64')
            ->with($date->getTimestamp());

        $protocolWriter->appendFieldValue($date, $buffer);
    }

    public function test_appendFieldValue_canHandleDateTimeImmutable()
    {
        $buffer = $this->createMock(Buffer::class);
        $protocolWriter = new ProtocolWriter();

        $date = new DateTimeImmutable();

        $buffer->expects($this->once())
            ->method('appendUint8')
            ->with(Constants::FIELD_TIMESTAMP);
        $buffer->expects($this->once())
            ->method('appendUint64')
            ->with($date->getTimestamp());

        $protocolWriter->appendFieldValue($date, $buffer);
    }

    public function test_appendFieldValue_canHandleInt32()
    {
        $buffer = $this->createMock(Buffer::class);
        $protocolWriter = new ProtocolWriter();

        $int = 42;

        $buffer->expects($this->once())
               ->method('appendUint8')
               ->with(Constants::FIELD_LONG_INT);
        $buffer->expects($this->once())
               ->method('appendInt32')
               ->with($int);

        $protocolWriter->appendFieldValue($int, $buffer);
    }

    public function test_appendFieldValue_canHandleInt64()
    {
        $buffer = $this->createMock(Buffer::class);
        $protocolWriter = new ProtocolWriter();

        $int = 2_157_483_647;

        $buffer->expects($this->once())
               ->method('appendUint8')
               ->with(Constants::FIELD_LONG_LONG_INT);
        $buffer->expects($this->once())
               ->method('appendInt64')
               ->with($int);

        $protocolWriter->appendFieldValue($int, $buffer);
    }
}
