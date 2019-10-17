<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;

class SameNodeAs
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
		$this->compareTable = $compareTable;
		$this->schema = $schema;
	}

	public function __invoke(Table $table)
	{
		$path = $this->schema->getRelationshipPath($this->compareTable->alias(), $table->alias());

		if ($path) {
			for ($i = 0; $i < count($path) - 1; $i++) {
				$firstTableAlias = $path[$i];
				$secondTableAlias = $path[$i + 1];

				$relationship = $this->schema->getRelationship($firstTableAlias, $secondTableAlias);

				if($relationship->tableHasOne($firstTableAlias, $secondTableAlias) === false) {
					return false;
				}
			}

			return true;
		}

		return false;
	}
}