<?php

use App\Core\Middleware\MiddlewareCollector;
use App\Core\Middleware\AuthMiddleware;
use App\Core\Middleware\JsonDecoder;

/** MIDDLEWARE IS RUN IN THE ORDER THEY APPEAR */

/** @var $middlewares MiddlewareCollector */

$middlewares->add(AuthMiddleware::class);
$middlewares->add(JsonDecoder::class);