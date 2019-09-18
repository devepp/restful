<?php

namespace App\Repositories;

use App\Domain\Asset;
use App\Domain\AssetId;
use Psr\SimpleCache\CacheInterface;

class CachedAssetRepository implements AssetRepositoryInterface
{
	/** @var CacheInterface */
	private $cache;
	/** @var AssetRepository */
	private $assets;

	/**
	 * CachedAssetRepository constructor.
	 * @param CacheInterface $cache
	 * @param AssetRepository $assetRepository
	 */
	public function __construct(CacheInterface $cache, AssetRepository $assetRepository)
	{
		$this->cache = $cache;
		$this->assets = $assetRepository;
	}

	public function getById(AssetId $assetId)
	{
		$asset = $this->cache->get($assetId->asString());

		if ($asset) {
			return $asset;
		}

		$asset = $this->assets->getById($assetId);

		if ($asset) {
			$this->cache->set($assetId->asString(), $asset);
			return $asset;
		}
	}

	public function getByIdOrFail(AssetId $assetId)
	{
		$asset = $this->getById($assetId);

		if ($asset) {
			return $asset;
		}

		throw new NotFoundException('Asset with id '.$assetId.' could not be found');
	}

	public function getAll()
	{
		return $this->assets->getAll();
	}

	public function add(Asset $asset): AssetId
	{
		$assetId = $this->assets->add($asset);

		$addedAsset = $this->assets->getById($assetId);

		$this->cache->set($assetId->asString(), $addedAsset);

		return $addedAsset->getId();

	}

	public function remove(AssetId $assetId)
	{
		$this->assets->remove($assetId);
		$this->cache->delete((string) $assetId);
	}

}