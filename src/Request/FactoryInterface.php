<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Request;

use \Psr\Http\Message\RequestInterface;

interface FactoryInterface
{
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
    ): RequestInterface;


}