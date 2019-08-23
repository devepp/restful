<?php

namespace App\Core;

use App\Core\Middleware\MiddlewareCollection;
use App\Core\Router\RouteDispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
	protected $middleware;
	/** @var RouteDispatcher */
	protected $routeDispatcher;
	/** @var ContainerInterface */
	protected $container;

	/**
	 * RequestHandler constructor.
	 * @param array $middleware
	 * @param ContainerInterface $container
	 */
	public function __construct(MiddlewareCollection $middlewareCollection, RouteDispatcher $dispatcher, ContainerInterface $container)
	{
		$this->middleware = $middlewareCollection->toArray();
		$this->routeDispatcher = $dispatcher;
		$this->container = $container;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		if (!empty($this->middleware)) {
			$middleware = $this->getMiddleware();
			return $middleware->process($request, $this);
		}

		// we may want to do something if dispatch doesn't return a response
		return $this->routeDispatcher->dispatch($request);
	}

	/**
	 * @return MiddlewareInterface
	 */
	private function getMiddleware()
	{
		$middlewareClass = array_shift($this->middleware);

		return $this->container->get($middlewareClass);
	}
}