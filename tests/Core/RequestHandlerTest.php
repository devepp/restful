<?php

namespace App\Core;

use App\Core\Container\Container;
use App\Core\Middleware\MiddlewareCollection;
use App\Core\Router\RouteCollection;
use App\Core\Router\RouteDispatcher;
use App\Core\Router\Router;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequestFactory;

class RequestHandlerTest extends TestCase
{
	public function testReturnsResponse()
	{
		$response = new Response();
		$middlewareCollection = new MiddlewareCollection([]);
		$routeDispatcherStub = $this->createMock(RouteDispatcher::class);
		$routeDispatcherStub->method('dispatch')->willReturn($response);
		$container = $this->createMock(Container::class);
		$responseFactory = new ResponseFactory();
		$handler = new RequestHandler($middlewareCollection, $routeDispatcherStub, $container, $responseFactory);

		$requestFactory = new ServerRequestFactory();

		$request = $requestFactory->createServerRequest('get', 'home', []);

		$returnedResponse = $handler->handle($request);

		$this->assertSame($response, $returnedResponse);
	}
}