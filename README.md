# Miinto Api Client

The Miinto Api client is a library that facilitates communication with the microservicies API in Miinto.
This client implements `\Psr\Http\Client\ClientInterface` so  it's complied with the PSR18 standard.

We can define several types of classes:
- Middleware - They are used before sending a request. You can modify requests header and body (e.g. Hmac signature)
- ResponseHandler - They are used after sending a request and base on response object. You can modify a response object. (e.g. Error response behaviour)
- Decoder - You can decode a response body content. (e.g. Json decoding)

## 1. Installation

```shell script
composer install miinto/api_client
```

## 2. Usage

## 2.1 Creating new client

Using `\Miinto\ApiClient\Factory` you can create two types of miinto clients:
- basic miinto client - you can define middleware and response handlers by your own.
- miinto client - the HMAC Middleware and the HandlerError are already set up. It means that all requests will
be automatically sign using HMAC Miinto headers. When response code will be < 200 or >= 400, the Response Exception will 
be thrown.   

**CAUTION:** To create any of client you should inject the http client with PSR18 standard. The recommendation is to add
to composer.json this package `"php-http/curl-client": "^2.0"`

Creating a basic client:
```php
use \Http\Discovery\HttpClientDiscovery;
use \Miinto\ApiClient\Factory;

$httpPsr18Client =  HttpClientDiscovery::find();
$miintoClient = Factory::createBaseClient($httpPsr18Client);
```

Creating a basic Miinto client:

```php
use \Http\Discovery\HttpClientDiscovery;
use \Miinto\ApiClient\Factory;

// Those credentials comes from Auth Service
$channelId = '';
$token = '';

$httpPsr18Client =  HttpClientDiscovery::find();
$miintoClient = Factory::createClient($channelId, $token, $httpPsr18Client);
```


## 2.2 Creating new request

For creating a new request you can use the `Miinto\ApiClient\Request\Factory` class. 

Create a request
```php
\Miinto\ApiClient\Request\Factory::create($httpMethod, $uri, $queryParameters = [], $bodyParameters = [], $requestHeaders = []);
```

An example:

```php
use \Miinto\ApiClient\Request\Factory;

$request = Factory::create(
    "PATCH", 
    "https://api-service.miinto.net", 
    [
        'locationId' => 'M!i!s-1203-22-22'
    ], 
    [
        'name' => 'Adidas store'
    ], 
    [
        'Cache-Control' => 'no-cache'
    ]
);

```

or

```php
use \Miinto\ApiClient\Request\Factory;

$request = Factory::patch(
    "https://api-service.miinto.net", 
    [
        'locationId' => 'M!i!s-1203-22-22'
    ], 
    [
        'name' => 'Adidas store'
    ], 
    [
        'Cache-Control' => 'no-cache'
    ]
);
```

## 2.3 Real life example
```php

use \Http\Discovery\HttpClientDiscovery;
use \Http\Discovery\Psr17FactoryDiscovery;
use \Miinto\ApiClient\Request\Factory as RequestFactory;
use \Miinto\ApiClient\Factory as ClientFactory;
use \Miinto\ApiClient\Response\Decoder\Json as JsonDecoder;

$requestFactory =  Psr17FactoryDiscovery::findRequestFactory();
$streamFactory =  Psr17FactoryDiscovery::findStreamFactory();
$requestFactory = new RequestFactory($requestFactory, $streamFactory);

$httpClient =  HttpClientDiscovery::find();
$client = ClientFactory::createClient($httpClient);

$request = $requestFactory->get('https://api-pms.miinto.net/status');
$response = $httpClient->sendRequest($request);

var_dump(JsonDecoder::decode($response));

/**
array(2) {
  ["meta"]=>
  array(2) {
    ["status"]=>
    string(7) "success"
    ["version"]=>
    string(3) "1.0"
  }
  ["data"]=>
  array(2) {
    ["name"]=>
    string(30) "product_management_service-api"
    ["status"]=>
    string(2) "OK"
  }
}
*/

```
