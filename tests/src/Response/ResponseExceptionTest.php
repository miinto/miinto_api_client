<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class ResponseExceptionTest extends TestCase
{
    public function testExceptionGetErrorContainer(): void
    {
        $data = ['a'=>'b','c'=>'d'];
        $exception = new \Miinto\ApiClient\Response\Exception($data);
        $this->assertEquals($data, $exception->getErrorContainer());
    }
}