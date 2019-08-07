<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class AssetsController
{
	private $dbConnection;

	public function __construct(\PDO $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function index(ServerRequestInterface $request)
	{
		$result = $this->dbConnection->query('SELECT * FROM as_assets LIMIT 10');

		$assets = $result->fetchAll();

		return new JsonResponse($assets);
	}

	public function store(ServerRequestInterface $request)
	{
		$new_record = $this->dbConnection->query('INSERT INTO as_assets (equipment_no, friendly_name) VALUES (123456, "my Equipment")');
		$request->getAttribute('name');
		if ($new_record) {
			return new JsonResponse($new_record, 201);
		}

		return $this->factory->createResponse(422, 'This is a bad request');
	}
}