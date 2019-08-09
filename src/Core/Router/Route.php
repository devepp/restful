<?php

namespace App\Core\Router;

class Route
{
	private $httpMethod;
	private $urlPattern;
	private $controllerName;
	private $methodName;

	/**
	 * Route constructor.
	 * @param $httpMethod
	 * @param $url
	 * @param $controllerName
	 * @param $methodName
	 */
	public function __construct($httpMethod, $urlPattern, $controllerName, $methodName)
	{
		$this->httpMethod = $httpMethod;
		$this->urlPattern = $urlPattern;
		$this->controllerName = $controllerName;
		$this->methodName = $methodName;
	}

	public function matches($requestedUrl): bool
	{
		// TODO if dynamic route (with {} ) then use fancy regex otherwise check if url is exact match
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
		// TODO regex to get values from requestedUrl
		// example:
		//		$this->urlPattern = '/assets/{asset_id}/vendors/{vendor_id}';
		//		$requestedUrl = '/assets/222/vendors/77';
		//		return [
		//			'asset_id' => '222',
		//			'vendor_id' => '77',
		//		];

		return ['id' => '54'];
	}
}