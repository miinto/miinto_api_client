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
     * Create a request
     *
     * @param string $httpMethod
     * @param string $uri
     * @param array $queryParameters
     * @param array $bodyParameters
     * @param array $headers
     *
     * @return RequestInterface
     */
    public function create(
        string $httpMethod,
        string $uri,
        array $queryParameters = [],
        array $bodyParameters = [],
        array $headers = []
    ): RequestInterface {
        if (\count($queryParameters) > 0) {
            $uri .= '?' . \http_build_query($queryParameters);
        }

        $request = $this->requestFactory->createRequest(\mb_strtoupper($httpMethod), $uri);

        return $this->populateRequestHeaders($this->populateRequestBody($request, $bodyParameters), $headers);
    }

    /**
     * @param string $uri
     * @param array $queryParameters
     * @param array $bodyParameters
     * @param array $headers
     * @return RequestInterface
     */
    public function get(
        string $uri,
        array $queryParameters = [],
        array $bodyParameters = [],
        array $headers = []
    ): RequestInterface {
        return $this->create('GET', $uri, $queryParameters, $bodyParameters, $headers);
    }

    /**
     * @param string $uri
     * @param array $queryParameters
     * @param array $bodyParameters
     * @param array $headers
     * @return RequestInterface
     */
    public function post(
        string $uri,
        array $queryParameters = [],
        array $bodyParameters = [],
        array $headers = []
    ): RequestInterface {
        return $this->create('POST', $uri, $queryParameters, $bodyParameters, $headers);
    }

    /**
     * @param string $uri
     * @param array $queryParameters
     * @param array $bodyParameters
     * @param array $headers
     * @return RequestInterface
     */
    public function patch(
        string $uri,
        array $queryParameters = [],
        array $bodyParameters = [],
        array $headers = []
    ): RequestInterface {
        return $this->create('PATCH', $uri, $queryParameters, $bodyParameters, $headers);
    }

    /**
     * @param string $uri
     * @param array $queryParameters
     * @param array $bodyParameters
     * @param array $headers
     * @return RequestInterface
     */
    public function put(
        string $uri,
        array $queryParameters = [],
        array $bodyParameters = [],
        array $headers = []
    ): RequestInterface {
        return $this->create('PUT', $uri, $queryParameters, $bodyParameters, $headers);
    }

    /**
     * @param string $uri
     * @param array $queryParameters
     * @param array $bodyParameters
     * @param array $headers
     * @return RequestInterface
     */
    public function delete(
        string $uri,
        array $queryParameters = [],
        array $bodyParameters = [],
        array $headers = []
    ): RequestInterface {
        return $this->create('DELETE', $uri, $queryParameters, $bodyParameters, $headers);
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