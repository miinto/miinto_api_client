#Miinto Api Client

Miinto Api client is a library for easy communication with Miinto REST api.



We can define few class types; 

| Class type | Base on  | Description                                                                      | An Example               |
|------------|----------|----------------------------------------------------------------------------------|--------------------------|
| Middlware  | request  | They are used before sending a request. You can modify requests header and body. | Hmac signature           |
| Policy     | response | You can defined a specific behaviour base on response object.                    | Error response behaviour |
| Decorator  | response | You can decorate a response body content.                                        | Json                     |




## 1. Instalation

```shell script
composer install miinto/api_client
```

## 2. Usage

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
