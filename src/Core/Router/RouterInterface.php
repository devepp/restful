<?php 

namespace App\Core\Router;

interface RouterInterface
{
	public function getRoute($requestedUrl, $httpMethod): Route;
}