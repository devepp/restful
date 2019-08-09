<?php

use Dotenv\Dotenv;
use App\Core\Container\Container;
use Psr\Container\ContainerInterface;
use App\Core\Middleware\AuthMiddleware;
use Zend\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ServerRequestInterface;
use App\Core\Middleware\JsonDecoder;
use App\Core\Router\RouteDispatcher;
use App\Core\Router\RouteCollector;
use App\Core\Router\RouteCollection;
use App\Core\Router\RouterInterface;
use App\Core\Router\Router;
use App\Core\RequestHandler;
use Zend\Diactoros\ServerRequestFactory;
use App\Controllers\AssetsController;
use App\Core\PDOManager;
use Narrowspark\HttpEmitter\SapiEmitter;

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
	RouteCollector::class => function(ContainerInterface $c) {
		return new RouteCollector();
	},
	ContainerInterface::class => function(ContainerInterface $c) {
		return $c;
	},
	RouterInterface::class => function(ContainerInterface $c) {
		return new Router($c->get(RouteCollection::class));
	},
	RouteCollection::class => function(ContainerInterface $c) {
		$routeCollector = $c->get(RouteCollector::class);
		require_once('../src/Config/routes.php');
		return $routeCollector->getCollection();
	},
	ServerRequestInterface::class => function(ContainerInterface $c) {
		return new AssetsController($c->get(\PDO::class));
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
	$container->get(JsonDecoder::class)
];

$routeDispatcher = $container->get(RouteDispatcher::class);

$requestHandler =  new RequestHandler($middleWare, $routeDispatcher);
$request = ServerRequestFactory::fromGlobals();
$response  = $requestHandler->handle($request);

$emmitter = new SapiEmitter();
$emmitter->emit($response);