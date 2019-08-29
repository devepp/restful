<?php

namespace App\Core;

use App\Core\Exceptions\HTTP\MethodNotAllowedException;
use App\Core\Exceptions\HTTP\NotFoundException;
use App\Core\Router\Route;
use App\Core\Router\RouteCollection;
use App\Core\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
	public function testReturnsARoute()
	{
		$matchingRoute = $this->routeMock(true, true);

		$routes = [$matchingRoute];
		$routeCollection = new RouteCollection($routes);
		$router = new Router($routeCollection);

		$returnedRoute = $router->getRoute('', 'GET');

		$this->assertSame($matchingRoute, $returnedRoute);
	}

	public function testDoesNotReturnARoute()
	{
		$notMatchingRoute = $this->routeMock(false, true);

		$routes = [$notMatchingRoute];
		$routeCollection = new RouteCollection($routes);
		$router = new Router($routeCollection);

		$this->expectException(NotFoundException::class);
		$router->getRoute('', 'GET');
	}

	public function testMethodNotAllowed()
	{
		$badMethodRoute = $this->routeMock(true, false);

		$routes = [$badMethodRoute];
		$routeCollection = new RouteCollection($routes);
		$router = new Router($routeCollection);

		$this->expectException(MethodNotAllowedException::class);
		$router->getRoute('', 'GET');
	}

	private function routeMock($matches, $methodAllowed)
	{
		$mockRoute = $this->createMock(Route::class);
		$mockRoute->method('matches')->willReturn($matches);
		$mockRoute->method('getParameters')->willReturn([]);
		$mockRoute->method('methodAllowed')->willReturn($methodAllowed);

		return $mockRoute;
	}
}
