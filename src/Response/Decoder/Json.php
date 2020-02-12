<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Decoder;

use \Psr\Http\Message\ResponseInterface;
use \Miinto\ApiClient\Response\Exception;

class Json implements DecoderInterface
{
    /**
     * @param ResponseInterface $response
     * @return mixed
     *
     * @throws Exception
     */
    public static function decode(ResponseInterface $response)
    {
        $data = \json_decode((string) $response->getBody(), true);
        if ($data === null) {
            throw new Exception(
                [], 'Response body has incorrect json format: ' . $response->getBody()->getContents()
            );
        }

        return $data;
    }
}