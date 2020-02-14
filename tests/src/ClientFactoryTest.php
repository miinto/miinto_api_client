<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class ClientFactoryTest extends TestCase
{
    /** @var \Http\Client\HttpClient  */
    protected $httpClient;

    public function setUp(): void
    {
        $this->httpClient = \Http\Discovery\HttpClientDiscovery::find();
    }

    public function testCreateBaseClient(): void
    {
        $this->assertInstanceOf(\Miinto\ApiClient\Client::class, \Miinto\ApiClient\Factory::createBaseClient($this->httpClient));
    }

    public function testCreateClient(): void
    {
        $this->assertInstanceOf(\Miinto\ApiClient\Client::class, \Miinto\ApiClient\Factory::createClient("test","asdasdsad",$this->httpClient));
    }
}