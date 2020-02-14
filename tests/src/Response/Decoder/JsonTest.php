<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testBaseDecorator($response, $responseAsString): void
    {
        $this->assertEquals($responseAsString, \Miinto\ApiClient\Response\Decoder\Json::decode($response));
    }

    /**
     * @dataProvider data2
     */
    public function testBaseDecoratorWithError($response): void
    {
        $this->expectException(\Miinto\ApiClient\Response\Exception::class);
        \Miinto\ApiClient\Response\Decoder\Json::decode($response);
    }

    /**
     * @return array
     */
    public function data()
    {
        $data = ['a' => 'b', 'c' => 'd'];
        $response = \Http\Discovery\Psr17FactoryDiscovery::findResponseFactory()->createResponse(200);
        $stream = (\Http\Discovery\Psr17FactoryDiscovery::findStreamFactory())->createStream(\json_encode($data));

        return [
            [$response->withBody($stream), $data]
        ];
    }

    /**
     * @return array
     */
    public function data2()
    {
        $response = \Http\Discovery\Psr17FactoryDiscovery::findResponseFactory()->createResponse(200);
        $stream = (\Http\Discovery\Psr17FactoryDiscovery::findStreamFactory())->createStream("1;4;5;6;7");

        return [
            [$response->withBody($stream)]
        ];
    }
}