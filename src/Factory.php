<?php

declare (strict_types=1);

namespace Miinto\ApiClient;

use \Psr\Http\Client\ClientInterface;
use \Miinto\ApiClient\Middleware\Factory as MiddlewareFactory;
use \Miinto\ApiClient\Response\Handler\Factory as ResponseHandlerFactory;

class Factory
{
    /**
     * Create a basic client
     *
     * @param ClientInterface $client
     * @param array $requestMiddleware
     * @param array $responseHandlers
     *
     * @return Client
     */
    public static function createBaseClient(
        ClientInterface $client,
        array $requestMiddleware = [],
        array $responseHandlers = []
    ): Client {
        return new Client($client, $requestMiddleware, $responseHandlers);
    }

    /**
     * Create a client with Hmac signature
     *
     * @param string $channelId
     * @param string $token
     * @param ClientInterface $client
     * @param array $requestMiddleware
     * @param array $responseHandlers
     *
     * @return Client
     */
    public static function createClient(
        string $channelId,
        string $token,
        ClientInterface $client,
        array $requestMiddleware = [],
        array $responseHandlers = []
    ): Client {
        return self::createBaseClient(
            $client,
            [
                MiddlewareFactory::createHmac($channelId, $token)
            ],
            [
                ResponseHandlerFactory::createError()
            ]
        );
    }
}