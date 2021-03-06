<?php

namespace App\Core\Router;

class Route
{
	private $httpMethod;
	private $urlPattern;
	private $controllerName;
	private $methodName;
	private $urlSegments = [];

	/**
	 * Route constructor.
	 * @param $httpMethod
	 * @param $urlPattern
	 * @param $controllerName
	 * @param $methodName
	 */
	public function __construct($httpMethod, $urlPattern, $controllerName, $methodName)
	{
		$this->httpMethod = $httpMethod;
		$this->urlPattern = $urlPattern;
		$this->controllerName = $controllerName;
		$this->methodName = $methodName;
		$this->parseUrlPattern($this->urlPattern);
	}

	public function matches($requestedUrl): bool
	{
		$requestedUrl = trim($requestedUrl, '/');

		$requestedUrlArray = explode('/', $requestedUrl);

		if (count($requestedUrlArray) != count($this->urlSegments)) {
			return false;
		}

		foreach ($this->urlSegments as $index => $segment) {
			if (!$segment->matches($requestedUrlArray[$index])) {
				return false;
			}
		}
		return true;
	}

	public function methodAllowed($httpMethod): bool
	{
		return $httpMethod == $this->httpMethod;
	}

	public function getController(): string
	{
		return $this->controllerName;
	}

	public function getMethod(): string
	{
		return $this->methodName;
	}

	public function getParameters($requestedUrl): array
	{
		$parameters = [];

		$requestedUrl = trim($requestedUrl, '/');
		$requestedUrlArray = explode('/', $requestedUrl);

		foreach ($this->urlSegments as $index => $segment) {
			$parameters = array_merge($parameters, $segment->getParameters($requestedUrlArray[$index]));
		}
		return $parameters;
	}

	private function parseUrlPattern($urlPattern)
	{
		$urlPattern = trim($urlPattern, '/');

		$urlArray = explode('/', $urlPattern);

		foreach ($urlArray as $urlSegment) {
			$this->urlSegments[] = UrlSegment::makeUriSegment($urlSegment);
		}
	}
}