<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class RequestFactoryTest extends TestCase
{
    /** @var \Miinto\ApiClient\Request\Factory */
    protected $factory;

    public function setUp(): void
    {
        $requestFactory = \Http\Discovery\Psr17FactoryDiscovery::findRequestFactory();
        $streamFactory = \Http\Discovery\Psr17FactoryDiscovery::findStreamFactory();
        $this->factory = new \Miinto\ApiClient\Request\Factory($requestFactory, $streamFactory);
    }

    /**
     * @dataProvider data
     */
    public function testCreateRequest($method, $uri, $queryParams, $bodyParams, $headers): void
    {
        $request = $this->factory->create($method, $uri, $queryParams, $bodyParams, $headers);
        $this->assertInstanceOf(\Psr\Http\Message\RequestInterface::class, $request);
        $this->assertEquals($method, $request->getMethod());

        if (\count($queryParams) > 0) {
            $uri .= "?".\http_build_query($queryParams);
        }
        $this->assertEquals($uri, (string) $request->getUri());

        foreach ($headers as $headerKey => $headerValue){
          $this->assertEquals($headerValue, $request->getHeaderLine($headerKey));

        }

        if (\count($bodyParams) === 0) {
            $this->assertEquals('', (string)$request->getBody());
        } else {
            $this->assertEquals(json_encode($bodyParams), (string)$request->getBody());
        }
    }

    public function data()
    {
        return [
            // Simple test
            ['GET',     'http://test.pl', [], [], []],
            ['POST',    'http://test.pl', [], [], []],
            ['PUT',     'http://test.pl', [], [], []],
            ['PATCH',   'http://test.pl', [], [], []],
            ['DELETE',  'http://test.pl', [], [], []],

            // Headers test
            ['GET',     'http://test.pl', [], [], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['POST',    'http://test.pl', [], [], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['PUT',     'http://test.pl', [], [], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['PATCH',   'http://test.pl', [], [], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['DELETE',  'http://test.pl', [], [], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],

            // Body test
            ['GET',     'http://test.pl', [], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['POST',    'http://test.pl', [], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['PUT',     'http://test.pl', [], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['PATCH',   'http://test.pl', [], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['DELETE',  'http://test.pl', [], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],

            // Query test
            ['GET',     'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['POST',    'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['PUT',     'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['PATCH',   'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],
            ['DELETE',  'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], []],

            // Full test
            ['GET',     'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['POST',    'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['PUT',     'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['PATCH',   'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
            ['DELETE',  'http://test.pl', ['a'=>1, 'b' => 'test', 'c' => ['d' =>2, 'e' => 3]], ['a'=>1, 1=>1, 'c' => [1,2,3], 'd' => [['e','f'],['g','h'],['i','j']] ], ['Keep-Alive' => 'yes', 'Connection' => 'keep-alive']],
        ];
    }
}