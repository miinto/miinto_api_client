<?php

declare (strict_types=1);

namespace Miinto\ApiClient;

use \Psr\Http\Client\ClientInterface;
use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Miinto\ApiClient\Middleware\MiddlewareInterface;
use \Miinto\ApiClient\Response\Handler\HandlerInterface;

class Client implements ClientInterface
{
    /** @var ClientInterface */
    protected $client;

    /** @var MiddlewareInterface[] */
    protected $requestMiddleware = [];

    /** @var HandlerInterface[] */
    protected $responseHandlers = [];

    /**
     * Client constructor.
     *
     * @param ClientInterface $client
     * @param array $requestMiddleware
     * @param array $responseHandlers
     */
    public function __construct(ClientInterface $client, array $requestMiddleware = [], array $responseHandlers = [])
    {
        $this->client = $client;

        foreach ($requestMiddleware as $middleware) {
            $this->addMiddleware($middleware);
        }

        foreach ($responseHandlers as $responseHandler) {
            $this->addPolicy($responseHandler);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return $this
     */
    private function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->requestMiddleware[] = $middleware;

        return $this;
    }

    /**
     * @param HandlerInterface $responseHandler
     *
     * @return $this
     */
    private function addPolicy(HandlerInterface $responseHandler): self
    {
        $this->responseHandlers[] = $responseHandler;

        return $this;
    }

    /**
     * Send http request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        foreach ($this->requestMiddleware as $middleware) {
            $request = $middleware->process($request);
        }

        $response = $this->client->sendRequest($request);

        foreach ($this->responseHandlers as $responseHandler) {
            $response = $responseHandler->handle($response);
        }

        return $response;
    }
}