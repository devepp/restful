<?php 

namespace App\Core\Router;

use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface {

	private $routes = [];

	public function __construct(array $routes = [])
	{
		$this->routes = $routes;
	}

	public function getRoute(ServerRequestInterface $request): RouteInterface
	{
		$uri = $request->getUri();
		$uriPath = $uri->getPath();
		$httpMethod = $request->getMethod();

		if (isset($this->routes[$httpMethod][$uriPath])) {
			return $this->routes[$httpMethod][$uriPath];
		}

		throw new \Exception('Route not found');
	}
}