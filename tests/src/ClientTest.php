<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;
use \Psr\Http\Message\RequestInterface;

class ClientTest extends TestCase
{
    /** @var \Http\Mock\Client  */
    protected $httpClient;

    /** @var \Psr\Http\Message\RequestFactoryInterface  */
    protected $httpRequestFactory;

    /** @var \Miinto\ApiClient\Client  */
    protected $client;

    /** @var RequestInterface  */
    protected $request;

    public function setUp(): void
    {
        $this->httpClient = new \Http\Mock\Client();
        $this->httpRequestFactory = \Http\Discovery\Psr17FactoryDiscovery::findRequestFactory();
        $this->client = new \Miinto\ApiClient\Client($this->httpClient);
        $this->request = $this->httpRequestFactory->createRequest('POST', 'http://test.pl');
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testClientSendRequest(): void
    {
        $this->assertInstanceOf( \Psr\Http\Message\ResponseInterface::class, $this->client->sendRequest($this->request));
    }

    public function testCreatingClientWithIncorrectMiddleware(): void
    {
        $this->expectException(\TypeError::class);
        new \Miinto\ApiClient\Client($this->httpClient, [1,2,3]);
    }

    public function testCreatingClientWithIncorrectResponseHandler(): void
    {
        $this->expectException(\TypeError::class);
        new \Miinto\ApiClient\Client($this->httpClient, [], [1,2,3]);
    }
}