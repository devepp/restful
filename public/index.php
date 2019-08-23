<?php

use Dotenv\Dotenv;
use App\Core\RequestHandler;
use Zend\Diactoros\ServerRequestFactory;
use Narrowspark\HttpEmitter\SapiEmitter;
use App\Core\Container\ContainerBuilder;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// .env file to store credentials
Dotenv::create('../')->load();

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

$requestHandler =  $container->get(RequestHandler::class);
$request = ServerRequestFactory::fromGlobals();

// the "magic" line where everything's run - Paul Epp, 2019
$response  = $requestHandler->handle($request);

$emitter = new SapiEmitter();
$emitter->emit($response);