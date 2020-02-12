<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class BaseTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testBaseDecorator($response, $responseAsString): void
    {
        $this->assertEquals($responseAsString, \Miinto\ApiClient\Response\Decoder\Base::decode($response));
    }

    /**
     * @return array
     */
    public function data()
    {
        $response = \Http\Discovery\Psr17FactoryDiscovery::findResponseFactory()->createResponse(200);
        $stream = (\Http\Discovery\Psr17FactoryDiscovery::findStreamFactory())->createStream('ok');
        $response = $response->withBody($stream);

        return [
            [$response, 'ok']
        ];
    }
}