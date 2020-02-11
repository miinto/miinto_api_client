<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Policy;

use \Psr\Http\Message\ResponseInterface;

interface PolicyInterface
{
    /**
     * Decorate response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function process(ResponseInterface $response): ResponseInterface;
}