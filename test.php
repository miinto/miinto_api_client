<?php

require_once './vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors','On');

$httpClient =  \Http\Discovery\HttpClientDiscovery::find();
$requestFactory =  \Http\Discovery\Psr17FactoryDiscovery::findRequestFactory();
$streamFactory =  \Http\Discovery\Psr17FactoryDiscovery::findStreamFactory();

$requestFactory = new \Miinto\ApiClient\Request\Factory($requestFactory, $streamFactory);

$urlToApi = 'https://api-pms.miinto.net/status';
$request = $requestFactory->get($urlToApi);


$client = new \Miinto\ApiClient\Client(
    $httpClient,
    [
        \Miinto\ApiClient\Middleware\Factory::createHmac('asdasd', 'asdasdasd')
    ],
    [
        \Miinto\ApiClient\Response\Handler\Factory::createError()
    ]
);

$response = $httpClient->sendRequest($request);
$result = \Miinto\ApiClient\Response\Decoder\Json::decode($response);

var_dump($result);exit;