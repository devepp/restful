<?php

namespace App\Core\Router;


class RouteCollector
{
	protected $get = [];
	protected $post = [];
	protected $put = [];
	protected $delete = [];

	public function add($httpMethod, $url, $controller, $method)
	{
		switch (strtoupper($httpMethod)) {
			case "GET" :
				$this->get($url, $controller, $method);
				break;

			case "POST" :
				$this->post($url, $controller, $method);
				break;

			case "PUT" :
				$this->put($url, $controller, $method);
				break;

			case "DELETE" :
				$this->delete($url, $controller, $method);
				break;

			default :
				throw new \Exception();
		}
	}

	public function get($url, $controller, $method)
	{
		$this->get[$url] = new Route($controller, $method);
	}

	public function post($url, $controller, $method)
	{
		$this->post[$url] = new Route($controller, $method);
	}

	public function put($url, $controller, $method)
	{
		$this->put[$url] = new Route($controller, $method);
	}

	public function delete($url, $controller, $method)
	{
		$this->delete[$url] = new Route($controller, $method);
	}

	private function createRegex(string $url_string)
	{
		return preg_replace('({\w})', '([^/]+)', $url_string);
	}
}