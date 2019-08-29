<?php

namespace App\Core;

use App\Core\Router\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
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
}