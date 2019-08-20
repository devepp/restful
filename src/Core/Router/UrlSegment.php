<?php

namespace App\Core\Router;


class UrlSegment
{
	protected $uriSegment;

	/**
	 * Uri constructor.
	 * @param $uriSegment
	 */
	public function __construct($uriSegment)
	{
		$this->uriSegment = $uriSegment;
	}

	public static function makeUriSegment($uri)
	{
		if (strpos($uri, '{') !== false && strpos($uri, '}') !== false){
			return new DynamicUrlSegment($uri);
		}
		return new self($uri);
	}

	public function matches($uri)
	{
		return $uri == $this->uriSegment;
	}

	public function getParameters($uriSegment)
	{
		return [];
	}
}