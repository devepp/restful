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
}