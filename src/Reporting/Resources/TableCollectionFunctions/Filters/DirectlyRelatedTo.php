<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;

class DirectlyRelatedTo
{
	/** @var Schema */
	private $schema;

	/** @var Table */
	private $compareTable;

	/**
	 * DirectlyRelatedTo constructor.
	 * @param Table $compareTable
	 * @param Schema $schema
	 */
	public function __construct(Table $compareTable, Schema $schema)
	{
		$this->compareTable = $compareTable;
		$this->schema = $schema;
	}

	public function __invoke(Table $table)
	{
		return $this->schema->hasDirectRelationship($table->alias(), $this->compareTable->alias());
	}
}