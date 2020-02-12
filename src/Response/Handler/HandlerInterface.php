<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Handler;

use \Psr\Http\Message\ResponseInterface;

interface HandlerInterface
{
    /**
     * Handle response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function handle(ResponseInterface $response): ResponseInterface;
}