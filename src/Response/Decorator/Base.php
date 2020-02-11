<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Decorator;

use \Psr\Http\Message\ResponseInterface;
use \Miinto\ApiClient\Response\Exception;

class Json implements DecoratorInterface
{
    /**
     * Decorate response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public static function decorate(ResponseInterface $response)
    {
        return $response->getBody()->getContents();
    }
}