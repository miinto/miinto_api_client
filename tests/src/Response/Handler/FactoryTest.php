<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class FactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $this->assertInstanceOf(\Miinto\ApiClient\Response\Handler\Error::class, \Miinto\ApiClient\Response\Handler\Factory::createError());
    }


}