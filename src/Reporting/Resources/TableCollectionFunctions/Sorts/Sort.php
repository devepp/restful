<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Sorts;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;

abstract class Sort
{
	public static function byDistanceTo(Table $table, TableCollection $contextTables)
	{
		return new DistanceTo($table, $contextTables);
	}
}