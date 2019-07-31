<?php

namespace App\Core\Router;

class Route implements RouteInterface {

	private $controllerName;
	private $methodName;

	public function __construct($controllerName, $methodName)
	{
		$this->controllerName = $controllerName;
		$this->methodName = $methodName;
	}

	public function getController() 
	{
		return $this->controllerName;
	}

	public function getMethod() 
	{
		return $this->methodName;
	}
}