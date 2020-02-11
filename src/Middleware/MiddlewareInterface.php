<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Middleware;

use \Psr\Http\Message\RequestInterface;

interface MiddlewareInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function process(RequestInterface $request): RequestInterface;
}