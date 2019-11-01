<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\TableFilterInterface;

abstract class Filter
{
	public static function byDirectRelationTo(Table $compareTable)
	{
		return new DirectlyRelatedTo($compareTable);
	}

	public static function excludeTables(TableCollection $tablesToExclude)
	{
		return new Exclude($tablesToExclude);
	}

	public static function excludeTable(Table $tableToExclude)
	{
		return new Exclude(TableCollection::fromArray([$tableToExclude]));
	}

	public static function byFilters(TableFilterInterface ...$filters)
	{
		return new FilterCombo($filters);
	}

	public static function byRelatedTo(Table $table, TableCollection $contextTables)
	{
		// TODO change relatedTo to use TableCollection instead of Schema
		throw new \Exception('byRelatedTo still needs some work');
//		return new RelatedTo($table, $contextTables);
	}

	public static function bySameNodeAs(Table $table, TableCollection $contextTables)
	{
		return new SameNodeAs($table, $contextTables);
	}

	public static function byAliases(array $tableAliases)
	{
		return function (Table $table) use ($tableAliases) {
			return \in_array($table->alias(), $tableAliases);
		};
	}
}