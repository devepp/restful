<?php

namespace App\Reporting\Resources\TableCollectionFunctions;

use App\Reporting\Resources\Table;

interface TableFilterInterface
{
	/**
	 * @param Table $table
	 * @return bool
	 */
	public function __invoke(Table $table);
}