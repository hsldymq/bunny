<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Bunny;

use Bunny\Async\Client;
use Bunny\Exception\ClientException;
use Bunny\Test\Library\AsynchronousClientHelper;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;

class AsyncAuthenticationTest extends TestCase
{
    /**
     * @var AsynchronousClientHelper
     */
    private $helper;

    public function setUp(): void
    {
        parent::setUp();

        $this->helper = new AsynchronousClientHelper();
    }

    public function testAMQPLAINAuthentication()
    {
        $loop = Factory::create();
        $client = $this->helper->createClient($loop);
        
        $connected = false;
        $client->connect()->then(function (Client $client) use (&$connected) {
            $connected = true;
            return $client->disconnect();
        });
        
        $loop->run();
        
        $this->assertTrue($connected);
    }

    public function testPLAINAuthentication()
    {
        // Skip the test if the environment variable AUTH_TEST is not set to "plain"
        if (getenv('AUTH_TEST') !== 'plain') {
            $this->markTestSkipped('Skipped because env var AUTH_TEST not set to "plain"');
        }

        $loop = Factory::create();
        $client = $this->helper->createClient($loop);
        
        $connected = false;
        $client->connect()->then(function (Client $client) use (&$connected) {
            $connected = true;
            return $client->disconnect();
        });
        
        $loop->run();
        
        $this->assertTrue($connected);
    }

    public function testInvalidCredentials()
    {
        $loop = Factory::create();
        
        $options = $this->helper->getDefaultOptions();
        $options['user'] = 'invalid_user';
        $options['password'] = 'invalid_password';
        
        $client = $this->helper->createClient($loop, $options);
        
        $authFailed = false;
        $client->connect()->then(
            function () {
                $this->fail('Should not connect with invalid credentials');
            },
            function ($error) use (&$authFailed) {
                $this->assertInstanceOf(ClientException::class, $error);
                $authFailed = true;
            }
        );
        
        $loop->run();
        
        $this->assertTrue($authFailed);
    }

    public function testAuthenticationMechanismSelection()
    {
        // This test verifies that the asynchronous client can correctly select the available authentication mechanism.
        $loop = Factory::create();
        $client = $this->helper->createClient($loop);
        
        $connected = false;
        $client->connect()->then(function (Client $client) use (&$connected) {
            $connected = true;
            return $client->disconnect();
        });
        
        $loop->run();
        
        $this->assertTrue($connected);
    }
}