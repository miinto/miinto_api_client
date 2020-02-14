<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Decoder;

use \Psr\Http\Message\ResponseInterface;

interface DecoderInterface
{
    /**
     * Decoder response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public static function decode(ResponseInterface $response);
}