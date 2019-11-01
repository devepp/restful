<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Maps;

abstract class Map
{
	public static function toAliases()
	{
		return new ToAliases();
	}
}