<?php

namespace App;

interface RouteInterface {

	public function getController();

	public function getMethod();
}