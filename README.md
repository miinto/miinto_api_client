#Miinto Api Client

The Miinto Api client is a library that facilitates communication with the microservicies API interface in Miinto.
This client implements `\Psr\Http\Client\ClientInterface` so  it's complied with the PSR18 standard.

We can define few class types:
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
- miinto client - here you have already set up the HMAC Middleware and the HandlerError. It means that all requests will
be automatically sign using HMAC Miinto headers. When response code will be < 200 or >= 400, the Response Exception will 
be thrown.   

**CAUTION:** To create any of client you should inject the http client with PSR18 standard. The recommendation is to 
require two packages:

```json
  "nyholm/psr7": "^1.2",
  "php-http/curl-client": "^2.0"
```


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

For creating a new request you can use the `Miinto\ApiClient\Request\Factory` class; This class implements

```php
use \Miinto\ApiClient\Request\Factory;

$request = Factory::create($httpMethod, $uri, $queryParameters, $bodyParameters, $requestHeaders);
```

An example:
```php
use \Miinto\ApiClient\Request\Factory;

$request = Factory::create(
    "PATCH", 
    "https://api-service.miinto.net", 
    [
        'locationId' => 'M!is-1203-22-22'
    ], 
    [
        'stock' => 100
    ], 
    [
        'Cache-Control' => 'no-cache'
    ]
);

or

$request = Factory::patch(
    "https://api-service.miinto.net", 
    [
        'locationId' => 'M!is-1203-22-22'
    ], 
    [
        'stock' => 100
    ], 
    [
        'Cache-Control' => 'no-cache'
    ]
);
```












## 2.1 Usage
```php

use \Http\Discovery\HttpClientDiscovery;
use \Http\Discovery\Psr17FactoryDiscovery;

$httpClient =  HttpClientDiscovery::find();
$requestFactory =  Psr17FactoryDiscovery::findRequestFactory();
$streamFactory =  Psr17FactoryDiscovery::findStreamFactory();

$requestFactory = new \Miinto\ApiClient\Request\Factory($requestFactory, $streamFactory);

$urlToApi = 'https://api-pms.miinto.net/status';
$request = $requestFactory->get($urlToApi);


$client = new \Miinto\ApiClient\Client(
    $httpClient,
    [
        \Miinto\ApiClient\Middleware\Factory::createHmac('asdasd', 'asdasdasd')
    ],
    [
        \Miinto\ApiClient\Response\Policy\Factory::createError()
    ]
);

$response = $httpClient->sendRequest($request);
$result = \Miinto\ApiClient\Response\Decorator\Json::decode($response);

var_dump($result);exit;


```
