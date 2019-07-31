<?php

namespace App\Core\Router;

interface RouteInterface {

	public function getController();

	public function getMethod();
}