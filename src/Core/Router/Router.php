<?php 

namespace App\Core\Router;

use App\Core\Exceptions\HTTP\MethodNotAllowedException;
use App\Core\Exceptions\HTTP\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
	private $routes;

	public function __construct(RouteCollection $routes)
	{
		$this->routes = $routes;
	}

	public function getRoute($requestedUrl, $httpMethod): Route
	{
		/** @var Route $route */
		foreach ($this->routes->toArray() as $route) {
			if ($route->matches($requestedUrl) && $route->methodAllowed($httpMethod)) {
				return $route;
			}
		}

		/** @var Route $route */
		foreach ($this->routes->toArray() as $route) {
			if ($route->matches($requestedUrl) && !$route->methodAllowed($httpMethod)) {
				throw new MethodNotAllowedException('Method '.$httpMethod.' not allowed');
			}
		}

		throw new NotFoundException('Route not found');
	}
}