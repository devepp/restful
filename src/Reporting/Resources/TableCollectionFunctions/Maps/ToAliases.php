<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Maps;

use App\Reporting\Resources\Table;

class ToAliases
{
	public function __invoke(Table $table)
	{
		return $table->alias();
	}
}