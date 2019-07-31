<?php 

namespace App\Core\Router;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface {

	public function getRoute(ServerRequestInterface $request): RouteInterface;
}