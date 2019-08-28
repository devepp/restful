<?php

namespace App\Core;

use App\Core\Middleware\MiddlewareCollection;
use App\Core\Router\RouteDispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
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
	/** @var ResponseFactoryInterface */
	protected $responseFactory;

	/**
	 * RequestHandler constructor.
	 * @param MiddlewareCollection $middlewareCollection
	 * @param RouteDispatcher $dispatcher
	 * @param ContainerInterface $container
	 * @param ResponseFactoryInterface $responseFactory
	 */
	public function __construct(MiddlewareCollection $middlewareCollection, RouteDispatcher $dispatcher, ContainerInterface $container, ResponseFactoryInterface $responseFactory)
	{
		$this->middleware = $middlewareCollection->toArray();
		$this->routeDispatcher = $dispatcher;
		$this->container = $container;
		$this->responseFactory = $responseFactory;
	}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		if (!empty($this->middleware)) {
			$middleware = $this->getMiddleware();
			return $middleware->process($request, $this);
		}

		$response = $this->routeDispatcher->dispatch($request);

		if ($response instanceOf ResponseInterface) {
			return $response;
		}

		return $this->responseFactory->createResponse();
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