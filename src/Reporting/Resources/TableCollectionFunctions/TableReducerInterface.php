<?php

namespace App\Reporting\Resources\TableCollectionFunctions;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;

interface TableReducerInterface
{
	/**
	 * @param TableCollection $collectedTables
	 * @param Table $table
	 * @return TableCollection
	 */
	public function __invoke(TableCollection $collectedTables, Table $table);
}