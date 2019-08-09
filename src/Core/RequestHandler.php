<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
	protected $middleware = [];
	protected $container;

	/**
	 * RequestHandler constructor.
	 * @param array $middleware
	 * @param ContainerInterface $container
	 */
	public function __construct(array $middleware, ContainerInterface $container)
	{
		array_map([$this, 'addMiddleware'], $middleware);
		$this->container = $container;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		if ($m = $this->getMiddleware()) {
			return $m->process($request, $this);
		}

		throw new \Exception();
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