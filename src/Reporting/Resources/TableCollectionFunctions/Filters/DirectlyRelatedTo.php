<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Table;

class DirectlyRelatedTo
{
	/** @var Table */
	private $compareTable;

	/**
	 * DirectlyRelatedTo constructor.
	 * @param Table $compareTable
	 */
	public function __construct(Table $compareTable)
	{
		$this->compareTable = $compareTable;
	}

	public function __invoke(Table $table)
	{
		return $this->compareTable->relatedTo($table->alias()) || $table->relatedTo($this->compareTable->alias());
	}
}