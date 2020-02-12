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
}