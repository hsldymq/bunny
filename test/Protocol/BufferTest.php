<?php

declare(strict_types=1);

namespace Bunny\Test\Protocol;

use Bunny\Exception\BufferUnderflowException;
use Bunny\Protocol\Buffer;
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
{
    public function testGetLength(): void
    {
        $buf = new Buffer();
        self::assertEquals(0, $buf->getLength());

        $buf->append('a');
        self::assertEquals(1, $buf->getLength());

        $buf->append('a');
        self::assertEquals(2, $buf->getLength());

        $buf->read(1);
        self::assertEquals(2, $buf->getLength());

        $buf->read(2);
        self::assertEquals(2, $buf->getLength());

        $buf->consume(1);
        self::assertEquals(1, $buf->getLength());

        $buf->consume(1);
        self::assertEquals(0, $buf->getLength());
    }

    public function testIsEmpty(): void
    {
        $buf = new Buffer();
        self::assertTrue($buf->isEmpty());

        $buf->append('a');
        self::assertFalse($buf->isEmpty());

        $buf2 = new Buffer('a');
        self::assertFalse($buf->isEmpty());
    }

    public function testRead(): void
    {
        $buf = new Buffer('abcd');

        self::assertEquals('a', $buf->read(1));
        self::assertEquals(4, $buf->getLength());

        self::assertEquals('ab', $buf->read(2));
        self::assertEquals(4, $buf->getLength());

        self::assertEquals('abc', $buf->read(3));
        self::assertEquals(4, $buf->getLength());

        self::assertEquals('abcd', $buf->read(4));
        self::assertEquals(4, $buf->getLength());
    }

    public function testReadOffset(): void
    {
        $buf = new Buffer('abcd');

        self::assertEquals('a', $buf->read(1, 0));
        self::assertEquals(4, $buf->getLength());

        self::assertEquals('b', $buf->read(1, 1));
        self::assertEquals(4, $buf->getLength());

        self::assertEquals('c', $buf->read(1, 2));
        self::assertEquals(4, $buf->getLength());

        self::assertEquals('d', $buf->read(1, 3));
        self::assertEquals(4, $buf->getLength());
    }

    public function testReadThrows(): void
    {
        $this->expectException(BufferUnderflowException::class);
        $buf = new Buffer();
        $buf->read(1);
    }

    public function testConsume(): void
    {
        $buf = new Buffer('abcd');

        self::assertEquals('a', $buf->consume(1));
        self::assertEquals(3, $buf->getLength());

        self::assertEquals('bc', $buf->consume(2));
        self::assertEquals(1, $buf->getLength());

        self::assertEquals('d', $buf->consume(1));
        self::assertEquals(0, $buf->getLength());
    }

    public function testConsumeThrows(): void
    {
        $this->expectException(BufferUnderflowException::class);
        $buf = new Buffer();
        $buf->consume(1);
    }

    public function testDiscard(): void
    {
        $buf = new Buffer('abcd');

        $buf->discard(1);
        self::assertEquals('bcd', $buf->read($buf->getLength()));
        self::assertEquals(3, $buf->getLength());

        $buf->discard(2);
        self::assertEquals('d', $buf->read($buf->getLength()));
        self::assertEquals(1, $buf->getLength());

        $buf->discard(1);
        self::assertEquals(0, $buf->getLength());
        self::assertTrue($buf->isEmpty());
    }

    public function testDiscardThrows(): void
    {
        $this->expectException(BufferUnderflowException::class);
        $buf = new Buffer();
        $buf->discard(1);
    }

    public function testSlice(): void
    {
        $buf = new Buffer('abcd');

        $slice1 = $buf->slice(1);
        self::assertEquals('a', $slice1->read($slice1->getLength()));
        self::assertEquals(4, $buf->getLength());

        $slice2 = $buf->slice(2);
        self::assertEquals('ab', $slice2->read($slice2->getLength()));
        self::assertEquals(4, $buf->getLength());

        $slice3 = $buf->slice(3);
        self::assertEquals('abc', $slice3->read($slice3->getLength()));
        self::assertEquals(4, $buf->getLength());

        $slice4 = $buf->slice(4);
        self::assertEquals('abcd', $slice4->read($slice4->getLength()));
        self::assertEquals(4, $buf->getLength());
    }

    public function testSliceThrows(): void
    {
        $this->expectException(BufferUnderflowException::class);
        $buf = new Buffer();
        $buf->slice(1);
    }

    public function testConsumeSlice(): void
    {
        $buf = new Buffer('abcdef');

        $slice1 = $buf->consumeSlice(1);
        self::assertEquals('a', $slice1->read($slice1->getLength()));
        self::assertEquals(5, $buf->getLength());

        $slice2 = $buf->consumeSlice(2);
        self::assertEquals('bc', $slice2->read($slice2->getLength()));
        self::assertEquals(3, $buf->getLength());

        $slice3 = $buf->consumeSlice(3);
        self::assertEquals('def', $slice3->read($slice3->getLength()));
        self::assertEquals(0, $buf->getLength());
    }

    public function testConsumeSliceThrows(): void
    {
        $this->expectException(BufferUnderflowException::class);
        $buf = new Buffer();
        $buf->consumeSlice(1);
    }

    public function testAppend(): void
    {
        $buf = new Buffer();
        self::assertEquals(0, $buf->getLength());

        $buf->append('abcd');
        self::assertEquals(4, $buf->getLength());
        self::assertEquals('abcd', $buf->read(4));

        $buf->append('efgh');
        self::assertEquals(8, $buf->getLength());
        self::assertEquals('abcdefgh', $buf->read(8));
    }

    public function testAppendBuffer(): void
    {
        $buf = new Buffer();
        self::assertEquals(0, $buf->getLength());

        $buf->append(new Buffer('ab'));
        self::assertEquals(2, $buf->getLength());
        self::assertEquals('ab', $buf->read(2));

        $buf->append('cd');
        self::assertEquals(4, $buf->getLength());
        self::assertEquals('abcd', $buf->read(4));

        $buf->append(new Buffer('ef'));
        self::assertEquals(6, $buf->getLength());
        self::assertEquals('abcdef', $buf->read(6));
    }

    public function testReadUint8(): void
    {
        self::assertEquals(0xA9, (new Buffer("\xA9"))->readUint8());
    }

    public function testReadInt8(): void
    {
        self::assertEquals(0xA9 - 0x100, (new Buffer("\xA9"))->readInt8());
    }

    public function testConsumeUint8(): void
    {
        self::assertEquals(0xA9, (new Buffer("\xA9"))->consumeUint8());
    }

    public function testConsumeInt8(): void
    {
        self::assertEquals(0xA9 - 0x100, (new Buffer("\xA9"))->consumeInt8());
    }

    public function testAppendUint8(): void
    {
        self::assertEquals("\xA9", (new Buffer())->appendUint8(0xA9)->read(1));
    }

    public function testAppendInt8(): void
    {
        self::assertEquals("\xA9", (new Buffer())->appendInt8(0xA9 - 0x100)->read(1));
    }

    public function testReadUint16(): void
    {
        self::assertEquals(0xA978, (new Buffer("\xA9\x78"))->readUint16());
    }

    public function testReadInt16(): void
    {
        self::assertEquals(0xA978 - 0x10000, (new Buffer("\xA9\x78"))->readInt16());
    }

    public function testConsumeUint16(): void
    {
        self::assertEquals(0xA978, (new Buffer("\xA9\x78"))->consumeUint16());
    }

    public function testConsumeInt16(): void
    {
        self::assertEquals(0xA978 - 0x10000, (new Buffer("\xA9\x78"))->consumeInt16());
    }

    public function testAppendUint16(): void
    {
        self::assertEquals("\xA9\x78", (new Buffer())->appendUint16(0xA978)->read(2));
    }

    public function testAppendInt16(): void
    {
        self::assertEquals("\xA9\x78", (new Buffer())->appendInt16(0xA978)->read(2));
    }

    public function testReadUint32(): void
    {
        self::assertEquals(0xA9782361, (new Buffer("\xA9\x78\x23\x61"))->readUint32());
    }

    public function testReadInt32(): void
    {
        self::assertEquals(0xA9782361 - 0x100000000, (new Buffer("\xA9\x78\x23\x61"))->readInt32());
    }

    public function testConsumeUint32(): void
    {
        self::assertEquals(0xA9782361, (new Buffer("\xA9\x78\x23\x61"))->consumeUint32());
    }

    public function testConsumeInt32(): void
    {
        self::assertEquals(0xA9782361 - 0x100000000, (new Buffer("\xA9\x78\x23\x61"))->consumeInt32());
    }

    public function testAppendUint32(): void
    {
        self::assertEquals("\xA9\x78\x23\x61", (new Buffer())->appendUint32(0xA9782361)->read(4));
    }

    public function testAppendInt32(): void
    {
        self::assertEquals("\xA9\x78\x23\x61", (new Buffer())->appendInt32(0xA9782361)->read(4));
    }

    public function testReadUint64(): void
    {
        self::assertEquals(0x1978236134738525, (new Buffer("\x19\x78\x23\x61\x34\x73\x85\x25"))->readUint64());
    }

    public function testReadInt64(): void
    {
        self::assertEquals(-2, (new Buffer("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFE"))->readInt64());
    }

    public function testConsumeUint64(): void
    {
        self::assertEquals(0x1978236134738525, (new Buffer("\x19\x78\x23\x61\x34\x73\x85\x25"))->consumeUint64());
    }

    public function testConsumeInt64(): void
    {
        self::assertEquals(-2, (new Buffer("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFE"))->consumeInt64());
    }

    public function testAppendUint64(): void
    {
        self::assertEquals("\x19\x78\x23\x61\x34\x73\x85\x25", (new Buffer())->appendUint64(0x1978236134738525)->read(8));
    }

    public function testAppendInt64(): void
    {
        self::assertEquals("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFE", (new Buffer())->appendInt64(-2)->read(8));
    }

    public function testReadFloat(): void
    {
        self::assertEquals(1.5, (new Buffer("\x3F\xC0\x00\x00"))->readFloat());
    }

    public function testConsumeFloat(): void
    {
        self::assertEquals(1.5, (new Buffer("\x3F\xC0\x00\x00"))->consumeFloat());
    }

    public function testAppendFloat(): void
    {
        self::assertEquals("\x3F\xC0\x00\x00", (new Buffer())->appendFloat(1.5)->read(4));
    }

    public function testReadDouble(): void
    {
        self::assertEquals(1.5, (new Buffer("\x3F\xF8\x00\x00\x00\x00\x00\x00"))->readDouble());
    }

    public function testConsumeDouble(): void
    {
        self::assertEquals(1.5, (new Buffer("\x3F\xF8\x00\x00\x00\x00\x00\x00"))->consumeDouble());
    }

    public function testAppendDouble(): void
    {
        self::assertEquals("\x3F\xF8\x00\x00\x00\x00\x00\x00", (new Buffer())->appendDouble(1.5)->read(8));
    }
}
