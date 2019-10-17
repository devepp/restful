<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\TableFilterInterface;

class Exclude implements TableFilterInterface
{
	/** @var TableCollection */
	private $tablesToExclude;

	/**
	 * ExcludeTables constructor.
	 * @param TableCollection $tablesToExclude
	 */
	public function __construct(TableCollection $tablesToExclude)
	{
		$this->tablesToExclude = $tablesToExclude;
	}

	public function __invoke(Table $table)
	{
		return $this->tablesToExclude->hasTable($table) ? false : true;
	}
}