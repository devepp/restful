<?php

use Psr\Container\ContainerInterface;
use Zend\Diactoros\ResponseFactory;
use App\Core\Router\RouteCollector;
use App\Core\Router\RouteCollection;
use App\Core\Router\RouterInterface;
use App\Core\Router\Router;
use App\Controllers\AssetsController;
use App\Core\PDOManager;
use App\Core\Middleware\MiddlewareCollection;
use App\Core\Middleware\MiddlewareCollector;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

$factories = [
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
        require_once('../config/routes.php');
        return $routeCollector->getCollection();
    },
    ServerRequestInterface::class => function(ContainerInterface $c) {
        return new AssetsController($c->get(\PDO::class));
    },
    MiddlewareCollection::class => function(ContainerInterface $c) {
        $middlewares = $c->get(MiddlewareCollector::class);
        require_once('../config/middlewares.php');
        return $middlewares->collection();
    },
    PDO::class => function(ContainerInterface $c) {
        return PDOManager::getInstance()->getConnection();
    }
];