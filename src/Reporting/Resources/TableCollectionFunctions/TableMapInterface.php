<?php

namespace App\Reporting\Resources\TableCollectionFunctions;

use App\Reporting\Resources\Table;

interface TableMapInterface
{
	/**
	 * @param Table $table
	 * @return Table
	 */
	public function __invoke(Table $table);
}