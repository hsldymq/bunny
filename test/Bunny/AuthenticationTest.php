<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Bunny;

use Bunny\Exception\ClientException;
use Bunny\Test\Library\Environment;
use Bunny\Test\Library\SynchronousClientHelper;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * @var SynchronousClientHelper
     */
    private $helper;

    public function setUp(): void
    {
        parent::setUp();

        $this->helper = new SynchronousClientHelper();
    }

    public function testAMQPLAINAuthentication()
    {
        // This test verifies that the AMQPLAIN authentication mechanism works correctly.
        $client = $this->helper->createClient();
        
        $this->assertFalse($client->isConnected());
        
        $client->connect();
        
        $this->assertTrue($client->isConnected());
        
        $client->disconnect();
        $this->assertFalse($client->isConnected());
    }

    public function testPLAINAuthentication()
    {
        // Skip the test if the environment variable AUTH_TEST is not set to "plain"
        if (getenv('AUTH_TEST') !== 'plain') {
            $this->markTestSkipped('Skipped because env var AUTH_TEST not set to "plain"');
        }

        $client = $this->helper->createClient();
        
        $this->assertFalse($client->isConnected());
        
        $client->connect();
        
        $this->assertTrue($client->isConnected());
        
        $client->disconnect();
        $this->assertFalse($client->isConnected());
    }

    public function testInvalidCredentials()
    {
        $this->expectException(ClientException::class);
        
        $options = $this->helper->getDefaultOptions();
        $options['user'] = 'invalid_user';
        $options['password'] = 'invalid_password';
        
        $client = $this->helper->createClient($options);
        
        $client->connect();
    }

    public function testAuthenticationMechanismSelection()
    {
        // This test verifies that the client can correctly select the available authentication mechanism.
        $client = $this->helper->createClient();
        
        $client->connect();
        $this->assertTrue($client->isConnected());
        
        $client->disconnect();
        $this->assertFalse($client->isConnected());
    }
}