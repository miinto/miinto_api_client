<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Decoder;

use \Psr\Http\Message\ResponseInterface;

class Base implements DecoderInterface
{
    /**
     * Decorate response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public static function decode(ResponseInterface $response)
    {
        return (string) $response->getBody();
    }
}