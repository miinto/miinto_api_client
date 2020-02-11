<?php

require_once './vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors','On');

$urlToApi = 'https://proxy-pms.miinto.net/status';
$httpClient =  \Http\Discovery\HttpClientDiscovery::find();
$requestFactory =  \Http\Discovery\Psr17FactoryDiscovery::findRequestFactory();
$streamFactory =  \Http\Discovery\Psr17FactoryDiscovery::findStreamFactory();
$requestFactory = new \Miinto\ApiClient\Request\Factory($requestFactory, $streamFactory);
$request = $requestFactory->get($urlToApi);


$client = new \Miinto\ApiClient\Client($httpClient);



var_dump($httpClient->sendRequest($request)->getBody()->getContents());exit;