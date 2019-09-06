<?php

namespace App\Controllers;

use App\Core\Exceptions\HTTP\NotFoundException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use PDO;

class AssetsController
{
	private $dbConnection;

	public function __construct(PDO $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function index(ServerRequestInterface $request)
	{
		$result = $this->dbConnection->query('SELECT * FROM as_assets LIMIT '.$request->getAttribute('limit', 10));

		$assets = $result->fetchAll(PDO::FETCH_ASSOC);

		return new JsonResponse($assets);
	}

	public function store(ServerRequestInterface $request)
	{
		$new_record = $this->dbConnection->query('INSERT INTO as_assets (equipment_no, friendly_name) VALUES (123456, "my Equipment")');

		if ($new_record) {
			return new JsonResponse($new_record, 201);
		}

		return $this->factory->createResponse(422, 'This is a bad request');
	}

	public function show(ServerRequestInterface $request, $id)
	{
		$result = $this->dbConnection->query('SELECT * FROM as_assets WHERE id = '.$id);

		$asset = $result->fetch(PDO::FETCH_ASSOC);

		if ($asset) {
			return new JsonResponse($asset);
		}

		throw new NotFoundException('Asset with id '.$id.' could not be found');
	}
}