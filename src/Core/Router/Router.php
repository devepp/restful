<?php 

namespace App\Core\Router;

use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface {

	/**
	 * // Example Routes
	 *	private $routes = [
	 *		'POST' => [
	 *			'first/first_function' => ['first', 'doSomething']
	 *		],
	 *		'GET' => [
	 *			'first/first_function' => ['first', 'doSomethingElse'],
	 *		]
	 *	];
	 */
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
			list($controller, $method) = $this->routes[$httpMethod][$uriPath];
		} else {
			throw new \Exception('Route not found');
		}
		return new Route($controller, $method);
	}
}