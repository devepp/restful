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

// .env file to store credentials
Dotenv::create('../')->load();

// required by container
$classFactories = [
	ResponseFactoryInterface::class => function(ContainerInterface $c) {
		return new ResponseFactory();
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
	PDO::class => function(ContainerInterface $c) {
		return PDOManager::getInstance()->getConnection();
	}
];

$container = new Container($classFactories);

// auth checks for presence of header token and json decoder decodes into json if header says it should
$middleWare = [
	$container->get(AuthMiddleware::class),
	$container->get(JsonDecoder::class)
];

$routeDispatcher = $container->get(RouteDispatcher::class);

$requestHandler =  new RequestHandler($middleWare, $routeDispatcher);
$request = ServerRequestFactory::fromGlobals();

// the "magic" line where everything's run - Paul Epp, 2019
$response  = $requestHandler->handle($request);

$emitter = new SapiEmitter();
$emitter->emit($response);