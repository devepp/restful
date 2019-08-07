<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class AssetsController
{
	private $dbConnection;
	private $factory;

	public function __construct(\PDO $dbConnection, ResponseFactoryInterface $factory)
	{
		$this->dbConnection = $dbConnection;
		$this->factory = $factory;
	}

	/**
	 *
	 */
	public function index(ServerRequestInterface $request)
	{
		$result = $this->dbConnection->query('SELECT * FROM as_assets');

		$assets = $result->fetchAll();

		$response = $this->factory->createResponse(200);

		return $response->withBody($assets);
	}

	public function store(ServerRequestInterface $request)
	{
		$new_record = $this->dbConnection->query('INSERT INTO as_assets (equipment_no, friendly_name) VALUES (123456, "my Equipment")');

		if ($new_record) {
			return $this->factory->createResponse(201);
		}
		return $this->factory->createResponse(422, 'This is a bad request');
	}
}