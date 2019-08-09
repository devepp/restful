<?php
use App\Core\Router\RouteCollector;
use App\Core\Router\Route;
use App\Controllers\AssetsController;

//$routeCollector = new RouteCollector();
//$routeCollector->add('GET', '/assets', 'AssetsController', 'index');
$routes = [
	'GET' => [
		'/assets' => new Route(AssetsController::class, 'index'),
	],
	'POST' => [
		'/assets' => new Route(AssetsController::class, 'store')
	],
];