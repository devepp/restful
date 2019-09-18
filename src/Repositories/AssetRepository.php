<?php


namespace App\Repositories;


use App\Domain\Asset;
use App\Domain\AssetId;
use PDO;

class AssetRepository implements AssetRepositoryInterface
{
	/** @var PDO */
	private $dbConnection;

	/**
	 * AssetRepository constructor.
	 * @param PDO $dbConnection
	 */
	public function __construct(PDO $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function getById(AssetId $assetId)
	{
		$result = $this->dbConnection->query('SELECT * FROM as_assets WHERE id = '.$assetId);

		$asset = $result->fetch(PDO::FETCH_ASSOC);

		return $asset;
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
		$result = $this->dbConnection->query('SELECT * FROM as_assets LIMIT 10');

		return $result->fetchAll(PDO::FETCH_ASSOC);
	}

	public function add(Asset $asset): AssetId
	{
		if ($asset->getId()) {
			$this->dbConnection->query('UPDATE as_assets SET equipment_no = 123456, friendly_name = "my Equipment"');

			return $asset->getId();
		}

		$this->dbConnection->query('INSERT INTO as_assets (equipment_no, friendly_name) VALUES (123456, "my Equipment")');

		return new AssetId($this->dbConnection->lastInsertId());
	}

	public function remove(AssetId $assetId)
	{
		$this->dbConnection->query('DELETE FROM as_assets WHERE id = '.$assetId);
	}
}