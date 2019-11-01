<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;

class SameNodeAs
{

	/** @var Table */
	private $compareTable;

	/** @var TableCollection */
	private $availableTables;

	/**
	 * SameNodeAs constructor.
	 * @param Table $compareTable
	 * @param TableCollection $availableTables
	 */
	public function __construct(Table $compareTable, TableCollection $availableTables)
	{
		$this->compareTable = $compareTable;
		$this->availableTables = $availableTables;
	}

	public function __invoke(Table $table)
	{
		return $this->compareTable->sameNodeAs($table, $this->availableTables);
	}
}