<?php

namespace App\Core;

use App\Core\Router\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
   public function testReturnsParameters()
   {
      $route = new Route('GET', '/assets/{id}', 'AssetsController', 'index');

      $parameters = $route->getParameters('/assets/5');

      $expectReturnedArray = [
         'id' => '5'
      ];

      $this->assertEquals($parameters, $expectReturnedArray);
   }

   public function testDoesNotReturnsParameters()
   {
      $route = new Route('GET', '/assets/example', 'AssetsController', 'index');

      $parameters = $route->getParameters('/assets/5');

      $expectReturnedArray = [];

      $this->assertEquals($parameters, $expectReturnedArray);
   }

	public function testMethodAllowed()
	{
		$route = new Route('GET', '', '', '');
		$this->assertTrue($route->methodAllowed('GET'));
	}

	public function testMethodNotAllowed()
	{
		$route = new Route('GET', '', '', '');
		$this->assertFalse($route->methodAllowed('POST'));
	}

	public function testMatchesDoLovelyStuff()
	{
		$route = new Route('GET', 'assets/{id}', 'assets', 'show');

		$this->assertTrue($route->matches('assets/5'), 'URL is valid for method');
	}

	public function testMatchesDidntDoLovelyStuff()
	{
		$route = new Route('GET', 'assets/{id}', 'assets', 'show');
		$this->assertFalse($route->matches('assets'), 'URL is not valid for method');
	}

	public function testTrimSlashes()
	{
		$route = new Route('GET', 'assets/', 'assets', 'show');

		$this->assertTrue($route->matches('assets'), 'URL is valid for method');
	}
}
