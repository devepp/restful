<?php

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
use App\Core\PDOManager;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// echo 'You are a success!';

$entries = [
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
	 			'/assets' => ['AssetsController', 'index'],
	 		],
			'POST' => [
				'/assets' => ['AssetsController', 'store'],
			],
		]);
	}
];

$c = new Container($entries);

$middleWare = [
    $c->get(AuthMiddleware::class),
    $c->get(JsonDecoder::class),
    $c->get(RouteDispatcher::class),
];



$requestHandler =  new RequestHandler($middleWare, $c);
$request = ServerRequestFactory::fromGlobals();
$response  = $requestHandler->handle($request);

// superior work done by Matt, assisted by the Honorable Coach Tyler
$pdo = PDOManager::getInstance()->getConnection();
$results = $pdo->query('SELECT * FROM as_assets');

foreach ($results as $row) {
    echo json_encode($row);
}

// NEED ASSETS CONTROLLER