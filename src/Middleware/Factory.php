<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Middleware;

class Factory
{
    /**
     * @param string $channelId
     * @param string $token
     *
     * @return Hmac
     */
    public static function createHmac(string $channelId, string $token): Hmac
    {
        return new Hmac($channelId, $token);
    }
}