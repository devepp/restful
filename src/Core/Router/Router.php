<?php 

namespace App;

use Psr\Http\Message\ServerRequestInterface;

class Router {

	private $routes = [];

	public function __construct(array $routes = null)
	{
		$this->routes = $routes ?: [];
	}

	public function locate(ServerRequestInterface $request) 
	{
		$uri = $request->getUri();
		$uriPath = $uri->getPath();
		$method = $uri->getMethod();

		//TODO add 404 handler
		$controller = $this->routes[$uriPath];

		return new Route($controller, $method);
	}
}