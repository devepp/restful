<?php

namespace App\Core\Router;


class DynamicUrlSegment extends UrlSegment
{
	public function matches($uri)
	{
		return true;
	}

	public function getIndex()
	{
		return str_replace(['{', '}'], '', $this->uriSegment);
	}

	public function getParameters($uriSegment)
	{
		return [$this->getIndex() => $uriSegment];
	}
}