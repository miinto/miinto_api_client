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
        $data = \json_decode($response->getBody()->getContents(), true);
        if ($data === null) {
            throw new Exception(
                [], 'Response body has incorrect json format: ' . $response->getBody()->getContents()
            );
        }

        return $data;
    }
}