<?php

namespace App\Core\Router;


class RouteCollector
{
	protected $routes = [];

	public function add($httpMethod, $url, $controller, $method)
	{
		$this->routes[] = new Route($httpMethod, $url, $controller, $method);
	}

	public function get($url, $controller, $method)
	{
		$this->add('GET', $url, $controller, $method);
	}

	public function post($url, $controller, $method)
	{
		$this->add('POST', $url, $controller, $method);
	}

	public function put($url, $controller, $method)
	{
		$this->add('PUT', $url, $controller, $method);
	}

	public function delete($url, $controller, $method)
	{
		$this->add('DELETE', $url, $controller, $method);
	}

	public function resource($url, $controller)
	{
		$this->get($url, $controller, 'index');
		$this->post($url, $controller, 'store');
		$this->get($url.'/{id}', $controller, 'show');
		$this->put($url.'/{id}', $controller, 'update');
		$this->delete($url.'/{id}', $controller, 'delete');
	}

	public function addCollection(RouteCollection $collection): void
	{
		foreach ($collection->toArray() as $route) {
			$this->routes[] = $route;
		}
	}

	public function getCollection(): RouteCollection
	{
		return new RouteCollection($this->routes);
	}
}