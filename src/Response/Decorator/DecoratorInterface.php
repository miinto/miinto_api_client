<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Decorator;

use \Psr\Http\Message\ResponseInterface;

interface DecoratorInterface
{
    /**
     * Decorate response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public static function decorate(ResponseInterface $response);
}