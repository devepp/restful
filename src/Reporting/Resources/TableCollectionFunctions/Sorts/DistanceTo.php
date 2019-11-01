<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Sorts;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;

class DistanceTo
{
	/** @var Table */
	private $toTable;

	/** @var TableCollection */
	private $contextTables;

	/**
	 * DistanceTo constructor.
	 * @param Table $toTable
	 * @param TableCollection $contextTables
	 */
	public function __construct(Table $toTable, TableCollection $contextTables)
	{
		$this->toTable = $toTable;
		$this->contextTables = $contextTables;
	}

	public function __invoke(Table $a, Table $b)
	{
		$distanceA = $a->pathTo($this->toTable, $this->contextTables);

		$distanceB = $b->pathTo($this->toTable, $this->contextTables);

		return $distanceA->count() - $distanceB->count();
	}


}