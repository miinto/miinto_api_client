<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class ErrorTest extends TestCase
{
    /** @var \Miinto\ApiClient\Response\Handler\Error  */
    protected $handler;

    /**
     * Set up
     */
    public function setUp(): void
    {
        $this->handler = new \Miinto\ApiClient\Response\Handler\Error();
    }

    /**
     * @dataProvider correctData
     */
    public function testHandlerError($response): void
    {
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $this->handler->handle($response));
    }

    /**
     * @dataProvider invalidData
     */
    public function testHandlerErrorException($response): void
    {
        $this->expectException(\Miinto\ApiClient\Response\Exception::class);
        $this->handler->handle($response);
    }

    /**
     * @return array
     */
    public function correctData()
    {
        $resp = \Http\Discovery\Psr17FactoryDiscovery::findResponseFactory()->createResponse(200);

        return [
            [$resp],
            [$resp->withStatus(202)],
            [$resp->withStatus(206)],
            [$resp->withStatus(301)],
            [$resp->withStatus(302)]
        ];
    }

    /**
     * @return array
     */
    public function invalidData()
    {
        $resp = \Http\Discovery\Psr17FactoryDiscovery::findResponseFactory()->createResponse(100);
        return [
            [$resp],
            [$resp->withStatus(400)],
            [$resp->withStatus(500)],
        ];
    }

}