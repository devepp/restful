<?php

namespace App\Core\Router;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;

class RouteDispatcher
{
	private $container;
	private $router;

	public function __construct(ContainerInterface $container, RouterInterface $router)
	{
		$this->container = $container;
		$this->router = $router;
	}

	public function dispatch(ServerRequestInterface $request)
	{
		$requestedUrl = $request->getUri()->getPath();
		$httpMethod = $request->getMethod();

		$route = $this->router->getRoute($requestedUrl, $httpMethod);

		$reflectedController = new ReflectionClass($route->getController());
		$reflectedMethod = $reflectedController->getMethod($route->getMethod());
		$reflectedParameters = $reflectedMethod->getParameters();

		$params = [];
		foreach ($reflectedParameters as $parameter) {
			$params[] = $this->getParameter($parameter, $route->getParameters($requestedUrl), $request);
		}

		$controller = $this->container->get($route->getController());
		$method = $route->getMethod();

		return call_user_func_array([$controller, $method], $params);
	}

	private function getParameter(\ReflectionParameter $parameterReflection, array $urlParameters, ServerRequestInterface $request)
	{
		if ($parameterReflection->hasType()) {
			$parameterType = $parameterReflection->getType()->getName();

			if ($parameterType === ServerRequestInterface::class) {
				return $request;
			}

			return $this->container->get($parameterType);
		}

		if (isset($urlParameters[$parameterReflection->getName()])) {
			return $urlParameters[$parameterReflection->getName()];
		}

		throw new \Exception('Parameter '.$parameterReflection->getName().' could not be injected into the called method');
	}

}
