<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;

class RelatedTo
{
	/** @var Schema */
	private $schema;

	/** @var Table */
	private $compareTable;

	/**
	 * RelatedTablesFilter constructor.
	 * @param Schema $schema
	 * @param Table $compareTable
	 */
	public function __construct(Table $compareTable, Schema $schema)
	{
		$this->schema = $schema;
		$this->compareTable = $compareTable;
	}

	public function __invoke(Table $table)
	{
		return $this->schema->hasRelationship($table->alias(), $this->compareTable->alias());
	}
}