<?php

namespace App\Core\Router;


class RouteCollection
{
	private $routes = [];

	/**
	 * RouteCollection constructor.
	 * @param array $routes
	 */
	public function __construct(array $routes)
	{
		$this->routes = $routes;
	}

	public function toArray()
	{
		return $this->routes;
	}


}