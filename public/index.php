<?php

use Dotenv\Dotenv;
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
use App\Core\PDOManager;

require_once dirname(__DIR__) . '/vendor/autoload.php';

Dotenv::create('../')->load();

$classFactories = [
    AuthMiddleware::class => function (ContainerInterface $c) {
        return new AuthMiddleware($c->get(ResponseFactoryInterface::class));
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
	 			'/assets' => [AssetsController::class, 'index'],
	 		],
			'POST' => [
				'/assets' => [AssetsController::class, 'store'],
			],
		]);
	},
	AssetsController::class => function(ContainerInterface $c) {
		return new AssetsController($c->get(\PDO::class));
	},
	PDO::class => function(ContainerInterface $c) {
		return PDOManager::getInstance()->getConnection();
	}
];

$container = new Container($classFactories);

$middleWare = [
	$container->get(AuthMiddleware::class),
	$container->get(JsonDecoder::class),
	$container->get(RouteDispatcher::class),
];

$requestHandler =  new RequestHandler($middleWare, $container);
$request = ServerRequestFactory::fromGlobals();
$response  = $requestHandler->handle($request);

// NEED EMITTER