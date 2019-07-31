<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
	protected $middleware;
	protected $container;

	/**
	 * Request constructor.
	 * @param $middleware
	 * @param ContainerInterface $container
	 */
	public function __construct($middleware, ContainerInterface $container)
	{
		$this->middleware = $middleware;
		$this->container = $container;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		if ($m = $this->getMiddleware()) {
			return $m->process($request, $this);
		} else {
			throw new \Exception();
		}
	}

	private function getMiddleware()
	{
		return array_shift($this->middleware);
	}

}