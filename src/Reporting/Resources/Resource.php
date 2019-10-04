<?php

namespace App\Reporting\Resources;

use App\Reporting\Processing\QueryGroup;

class Resource
{
	/** @var QueryGroup[] */
	private $queryGroups;

	/**
	 * Resource constructor.
	 * @param QueryGroup[] $queryGroups
	 */
	public function __construct($queryGroups)
	{
		$this->queryGroups = $queryGroups;
	}

	public static function builder(Table $baseTable)
	{
		return new ResourceBuilder($baseTable);
	}

	/**
	 * @return QueryGroup[]
	 */
	public function getQueryGroups(): array
	{
		return $this->queryGroups;
	}
}