<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Request;

use \Psr\Http\Message\RequestFactoryInterface;
use \Psr\Http\Message\StreamFactoryInterface;
use \Psr\Http\Message\RequestInterface;

class Factory
{
    /** @var RequestFactoryInterface */
    protected $requestFactory;

    /** @var StreamFactoryInterface */
    protected $streamFactory;

    /**
     * Factory constructor.
     *
     * @param RequestFactoryInterface $requestFactory
     * @param StreamFactoryInterface $streamFactory
     */
    public function __construct(RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory)
    {
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function get(string $uri, array $parameters = [], array $headers = []): RequestInterface
    {
        if (\count($parameters) > 0) {
            $uri .= '?' . \http_build_query($parameters);
        }

        $request = $this->requestFactory->createRequest('GET', $uri);

        return $this->populateRequestHeaders($request, $headers);
    }

    /**
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function post(string $uri, array $parameters = [], array $headers = []): RequestInterface
    {
        $request = $this->requestFactory->createRequest('POST', $uri);

        return $this->populateRequestHeaders($this->populateRequestBody($request, $parameters), $headers);
    }

    /**
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function patch(string $uri, array $parameters = [], array $headers = []): RequestInterface
    {
        $request = $this->requestFactory->createRequest('PATCH', $uri);

        return $this->populateRequestHeaders($this->populateRequestBody($request, $parameters), $headers);
    }

    /**
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function put(string $uri, array $parameters = [], array $headers = []): RequestInterface
    {
        $request = $this->requestFactory->createRequest('PUT', $uri);

        return $this->populateRequestHeaders($this->populateRequestBody($request, $parameters), $headers);
    }

    /**
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function delete(string $uri, array $parameters = [], array $headers = []): RequestInterface
    {
        $request = $this->requestFactory->createRequest('DELETE', $uri);

        return $this->populateRequestHeaders($this->populateRequestBody($request, $parameters), $headers);
    }

    /**
     * @param RequestInterface $request
     * @param array $headers
     *
     * @return RequestInterface
     */
    private function populateRequestBody(RequestInterface $request, array $parameters = []): RequestInterface
    {
        if (\count($parameters) > 0) {
            $stream = $this->streamFactory->createStream(\json_encode($parameters));
            $request = $request->withBody($stream);
        }

        return $request;
    }

    /**
     * @param RequestInterface $request
     * @param array $headers
     *
     * @return RequestInterface
     */
    private function populateRequestHeaders(RequestInterface $request, array $headers = []): RequestInterface
    {
        $request = $request->withHeader('Content-Type', 'application/json');

        foreach ($headers as $headerKey => $headerValue) {
            $request = $request->withHeader($headerKey, $headerValue);
        }

        return $request;
    }
}