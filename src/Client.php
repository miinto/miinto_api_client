<?php

declare (strict_types=1);

namespace Miinto\ApiClient;

use \Psr\Http\Client\ClientInterface;
use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Miinto\ApiClient\Middleware\MiddlewareInterface;
use \Miinto\ApiClient\Response\Policy\PolicyInterface;

/**
 * Class Client
 *
 * @package Miinto\ApiClient
 */
class Client implements ClientInterface
{
    /** @var ClientInterface */
    protected $client;

    /** @var MiddlewareInterface[] */
    protected $requestMiddleware = [];

    /** @var array */
    protected $responsePolicies = [];

    /**
     * Client constructor.
     *
     * @param ClientInterface $client
     * @param array $requestMiddleware
     * @param array $responsePolicies
     */
    public function __construct(ClientInterface $client, array $requestMiddleware = [], array $responsePolicies = [])
    {
        $this->client = $client;

        foreach ($requestMiddleware as $middleware) {
            $this->addMiddleware($middleware);
        }

        foreach ($responsePolicies as $policy) {
            $this->addPolicy($policy);
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
     * @param PolicyInterface $policy
     *
     * @return $this
     */
    private function addPolicy(PolicyInterface $policy): self
    {
        $this->responsePolicies[] = $policy;

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

        foreach ($this->responsePolicies as $policy) {
            $response = $policy->process($response);
        }

        return $response;
    }
}