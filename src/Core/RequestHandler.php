<?php

namespace App\Core;

use App\Core\Router\RouteDispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
	protected $middleware = [];
	protected $routeDispatcher;

	/**
	 * RequestHandler constructor.
	 * @param array $middleware
	 * @param ContainerInterface $container
	 */
	public function __construct(array $middleware, RouteDispatcher $dispatcher)
	{
		array_map([$this, 'addMiddleware'], $middleware);
		$this->routeDispatcher = $dispatcher;
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
		return array_shift($this->middleware);
	}

	private function addMiddleware(MiddlewareInterface $middleware)
	{
		$this->middleware[] = $middleware;
	}
}