<?php

declare(strict_types = 1);

namespace Bunny\Test;

use Bunny\Exception\ClientException;
use Bunny\Test\Library\ClientHelper;
use Bunny\Test\Library\Environment;
use PHPUnit\Framework\TestCase;
use function file_exists;
use function in_array;
use function is_file;
use function putenv;
use function time;

class SSLTest extends TestCase
{

    private ClientHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->helper = new ClientHelper();
    }

    public function testConnect(): void
    {
        $this->expectNotToPerformAssertions();

        $options = $this->getOptions();

        $client = $this->helper->createClient($options);
        $client->connect();
        $client->disconnect();
    }

    public function testConnectWithMissingClientCert(): void
    {
        $options = $this->getOptions();
        if (!isset($options['ssl']['local_cert'])) {
            $this->markTestSkipped('No client certificate is used');
        }

        // let's try without client certificate - it should fail
        unset($options['ssl']['local_cert'], $options['ssl']['local_pk']);

        if (Environment::getSslTest() === 'client') {
            $this->expectException(ClientException::class);
        }

        $client = $this->helper->createClient($options);
        $client->connect();
        $client->disconnect();
    }

    public function testConnectToTcpPort(): void
    {
        $options = $this->getOptions();
        unset($options['port']);

        $this->expectException(ClientException::class);

        $client = $this->helper->createClient($options);
        $client->connect();
        $client->disconnect();
    }

    public function testConnectWithWrongPeerName(): void
    {
        putenv('SSL_PEER_NAME=not-existsing-peer-name' . time());
        $options = $this->getOptions();

        $this->expectException(ClientException::class);

        $client = $this->helper->createClient($options);
        $client->connect();
        $client->disconnect();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        // should we do SSL-tests
        if (!in_array(Environment::getSslTest(), ['yes', 'client'], true)) {
            $this->markTestSkipped('Skipped because env var SSL_TEST not set to "yes" or "client"');
        }

        // checking CA-file
        $caFile = Environment::getSslCa();

        $testsDir = __DIR__;
        $caFile   = $testsDir . '/' . $caFile;
        if (!file_exists($caFile) || !is_file($caFile)) {
            $this->fail('Missing CA file: "' . $caFile . '"');
        }

        $peerName = Environment::getSslPeerName();

        // minimal SSL-options
        $options = [
            'port' => 5673,
            'ssl'  => [
                // for tests we are using self-signed certificates
                'allow_self_signed' => true,
                'cafile'            => $caFile,
                'peer_name'         => $peerName,
            ],
        ];

        $certFile = Environment::getSslClientCert();
        $keyFile  = Environment::getSslClientKey();

        if (!empty($certFile) && !empty($keyFile)) {
            $certFile = $testsDir . '/' . $certFile;
            $keyFile  = $testsDir . '/' . $keyFile;
            if (!file_exists($certFile) || !is_file($certFile)) {
                $this->fail('Missing certificate file: "' . $certFile . '"');
            }

            if (!file_exists($keyFile) || !is_file($keyFile)) {
                $this->fail('Missing key file: "' . $keyFile . '"');
            }

            $options['ssl']['local_cert'] = $certFile;
            $options['ssl']['local_pk']   = $keyFile;
        }

        return $options;
    }

}
