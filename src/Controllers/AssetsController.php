<?php

namespace App\Controllers;

use App\Domain\Asset;
use App\Domain\AssetId;
use App\Repositories\AssetRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use PDO;

class AssetsController
{
	private $assets;

	public function __construct(AssetRepositoryInterface $assets)
	{
		$this->assets = $assets;
	}

	public function index(ServerRequestInterface $request)
	{
		$assets = $this->assets->getAll();

		return new JsonResponse($assets);
	}

	public function store(ServerRequestInterface $request)
	{
		$asset = new Asset();

		$assetId = $this->assets->add($asset);

		$newAsset = $this->assets->getById($assetId);

		return new JsonResponse($newAsset, 201);
	}

	public function show(ServerRequestInterface $request, $id)
	{
		$asset = $this->assets->getByIdOrFail(AssetId::fromString($id));

		return new JsonResponse($asset);
	}
}