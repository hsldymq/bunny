<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types = 1);

namespace Bunny\Test;

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use Bunny\Test\Library\ClientHelper;
use PHPUnit\Framework\TestCase;
use WyriHaximus\React\PHPUnit\RunTestsInFibersTrait;
use function str_repeat;

class ChannelTest extends TestCase
{

    use RunTestsInFibersTrait;

    private ClientHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->helper = new ClientHelper();
    }

    public function testClose(): void
    {
        $c = $this->helper->createClient();
        $c->connect();
        $c->channel()->close();

        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

    public function testExchangeDeclare(): void
    {
        $c = $this->helper->createClient();

        $ch = $c->connect()->channel();
        $this->assertTrue($c->isConnected());
        $ch->exchangeDeclare('test_exchange', 'direct', false, false, true);
        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

    public function testQueueDeclare(): void
    {
        $c = $this->helper->createClient();

        $ch = $c->connect()->channel();
        $this->assertTrue($c->isConnected());
        $ch->queueDeclare('test_queue', false, false, false, true);
        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

    public function testQueueBind(): void
    {
        $c = $this->helper->createClient();

        $ch = $c->connect()->channel();
        $this->assertTrue($c->isConnected());
        $ch->exchangeDeclare('test_exchange', 'direct', false, false, true);
        $this->assertTrue($c->isConnected());
        $ch->queueDeclare('test_queue', false, false, false, true);
        $this->assertTrue($c->isConnected());
        $ch->queueBind('test_exchange', 'test_queue');
        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

    public function testPublish(): void
    {
        $c = $this->helper->createClient();

        $ch = $c->connect()->channel();
        $this->assertTrue($c->isConnected());
        $ch->publish('test publish', []);
        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

    public function testConsume(): void
    {
        $c = $this->helper->createClient();

        $ch = $c->connect()->channel();
        $this->assertTrue($c->isConnected());
        $ch->queueDeclare('test_queue', false, false, false, true);
        $this->assertTrue($c->isConnected());
        $ch->consume(function (Message $msg, Channel $ch, Client $c): void {
            $this->assertEquals('hi', $msg->content);
        });
        $this->assertTrue($c->isConnected());
        $ch->publish('hi', [], '', 'test_queue');
        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

    public function testHeaders(): void
    {
        $c = $this->helper->createClient();

        $ch = $c->connect()->channel();
        $ch->queueDeclare('test_queue', false, false, false, true);
        $ch->consume(function (Message $msg, Channel $ch, Client $c): void {
            $this->assertTrue($msg->hasHeader('content-type'));
            $this->assertEquals('text/html', $msg->getHeader('content-type'));
            $this->assertEquals('<b>hi html</b>', $msg->content);
        });
        $ch->publish('<b>hi html</b>', ['content-type' => 'text/html'], '', 'test_queue');

        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

    public function testBigMessage(): void
    {
        $body = str_repeat('a', 10 << 20 /* 10 MiB */);

        $c = $this->helper->createClient();

        $ch = $c->connect()->channel();
        $ch->queueDeclare('test_queue', false, false, false, true);
        $ch->consume(function (Message $msg, Channel $ch, Client $c) use ($body): void {
            $this->assertEquals($body, $msg->content);
        });
        $ch->publish($body, [], '', 'test_queue');

        $this->assertTrue($c->isConnected());
        $c->disconnect();
        $this->assertFalse($c->isConnected());
    }

}
