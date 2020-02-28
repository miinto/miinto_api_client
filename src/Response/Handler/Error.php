<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Handler;

use \Psr\Http\Message\ResponseInterface;
use \Miinto\ApiClient\Response\Exception;

class Error implements HandlerInterface
{
    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function handle(ResponseInterface $response): ResponseInterface
    {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 400) {
            throw new Exception(
                [
                    'code' => $response->getStatusCode(),
                    'reasonPhrase' => $response->getReasonPhrase(),
                    'body' => $response->getBody()->getContents(),
                    'headers' => $response->getHeaders()
                ],
                'Invalid http response: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase()
            );
        }

        return $response;
    }
}