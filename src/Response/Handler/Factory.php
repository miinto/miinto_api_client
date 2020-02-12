<?php

declare (strict_types=1);

namespace Miinto\ApiClient\Response\Handler;

class Factory
{
    /**
     * @return Error
     */
    public static function createError(): Error
    {
        return new Error();
    }
}