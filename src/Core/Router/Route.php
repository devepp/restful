<?php

namespace App\Core\Router;

class Route implements RouteInterface {

	private $controllerName;
	private $methodName;
	private $parameters;

	/**
	 * Route constructor.
	 * @param $controllerName
	 * @param $methodName
	 * @param $parameters
	 */
	public function __construct($controllerName, $methodName, $parameters = [])
	{
		$this->controllerName = $controllerName;
		$this->methodName = $methodName;
		$this->parameters = $parameters;
	}

	public function getController() 
	{
		return $this->controllerName;
	}

	public function getMethod() 
	{
		return $this->methodName;
	}

	public function getParameters()
	{
		return $this->parameters;
	}
}