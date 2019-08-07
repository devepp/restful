<?php

use App\Core\Container\Alpha;
use App\Core\Container\Container;
use Psr\Container\ContainerInterface;
use App\Core\Middleware\AuthMiddleware;
use Zend\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use App\Core\Middleware\JsonDecoder;
use App\Core\Middleware\RouteDispatcher;
use App\Core\Router\RouterInterface;
use App\Core\Router\Router;
use App\Core\RequestHandler;
use Zend\Diactoros\ServerRequestFactory;
use App\Controllers\AssetsController;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// echo 'You are a success!';

$entries = [
    Alpha::class => function (ContainerInterface $c) {
        return new Alpha();
    },
	AuthMiddleware::class => function(ContainerInterface $c) {
		return new AuthMiddleware($c->get(ResponseFactoryInterface::class));
	},
	ResponseFactoryInterface::class => function(ContainerInterface $c) {
		return new ResponseFactory();
	},
	JsonDecoder::class => function(ContainerInterface $c) {
		return new JsonDecoder();
	},
	RouteDispatcher::class => function(ContainerInterface $c) {
		return new RouteDispatcher($c->get(ContainerInterface::class), $c->get(RouterInterface::class));
	},
	ContainerInterface::class => function(ContainerInterface $c) {
		return $c;
	},
	RouterInterface::class => function(ContainerInterface $c) {
		return new Router([
			'GET' => [
	 			'/assets' => ['AssetsController', 'index'],
	 		],
			'POST' => [
				'/assets' => ['AssetsController', 'store'],
			],
		]);
	},
	AssetsController::class => function(ContainerInterface $c) {
		return new AssetsController($c->get(\PDO::class));
	},
	\PDO::class => function(ContainerInterface $c) {
		return new AssetsController($c->get(\PDO::class));
	}
];

$c = new Container($entries);

//$alpha = $c->get(Alpha::class);
//echo $alpha->name;

$middleWare = [
	$c->get(AuthMiddleware::class),
	$c->get(JsonDecoder::class),
	$c->get(RouteDispatcher::class),
];



$requestHandler =  new RequestHandler($middleWare, $c);

$request = ServerRequestFactory::fromGlobals();

$response  = $requestHandler->handle($request);

var_dump($response);

// NEED ASSETS CONTROLLER