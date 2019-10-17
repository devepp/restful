<?php

namespace App\Reporting\Processing;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;

class SameNode
{
	/** @var Schema */
	private $schema;

	/**
	 * RelatedTablesInSameNode constructor.
	 * @param Schema $schema
	 */
	public function __construct(Schema $schema)
	{
		$this->schema = $schema;
	}

	public function __invoke(TableCollection $collectedTables, Table $table)
	{
		$tableAlias = $table->alias();

		foreach ($collectedTables as $collectedTable) {
			$collectedTableAlias = $collectedTable->alias();

			if ($this->schema->hasDirectRelationship($collectedTableAlias, $tableAlias)) {
				$relationship = $this->schema->getRelationship($collectedTableAlias, $tableAlias);
				if ($relationship->tableHasOne($collectedTableAlias, $tableAlias)) {
					$collectedTables->addTable($table);
				}
			}
		}

		return $collectedTables;
	}
}