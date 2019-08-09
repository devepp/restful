<?php

namespace App\Core\Middleware;

use App\Core\Router\RouterInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteDispatcher implements MiddlewareInterface
{
	private $container;
	private $router;

	public function __construct(ContainerInterface $container, RouterInterface $router)
	{
		$this->container = $container;
		$this->router = $router;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$route = $this->router->getRoute($request);
		$controller =  $this->container->get($route->getController());
		$method = $route->getMethod();

		return $controller->$method($request);
	}

}
