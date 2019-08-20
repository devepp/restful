<?php

use App\Core\Router\RouteCollector;

/** @var $routeCollector RouteCollector */

$routeCollector->get('/assets', 'App\Controllers\AssetsController', 'index');
$routeCollector->post('/assets', 'App\Controllers\AssetsController', 'store');
$routeCollector->get('/assets/{id}', 'App\Controllers\AssetsController', 'show');