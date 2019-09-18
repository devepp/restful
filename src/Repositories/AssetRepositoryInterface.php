<?php


namespace App\Repositories;

use App\Domain\Asset;
use App\Domain\AssetId;

interface AssetRepositoryInterface
{
	public function getById(AssetId $id);
	public function getByIdOrFail(AssetId $id);
	public function getAll();
	public function add(Asset $data): AssetId;
	public function remove(AssetId $id);
}