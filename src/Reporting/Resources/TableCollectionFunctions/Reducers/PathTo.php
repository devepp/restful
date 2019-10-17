<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Reducers;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\Filters\DirectlyRelatedTo;
use App\Reporting\Resources\TableCollectionFunctions\TableReducerInterface;

class PathTo implements TableReducerInterface
{
	/** @var Schema */
	private $schema;
	/** @var Table[] */
	private $tables;

	/**
	 * PathTo constructor.
	 * @param Schema $schema
	 * @param $tables
	 */
	public function __construct(Schema $schema, $tables)
	{
		$this->schema = $schema;
		$this->tables = $tables;
	}


	public function __invoke(TableCollection $collectedTables, Table $table)
	{
		//TODO make sure this works or fix it
		throw new \Exception('may not be suitable for production yet');

		if ($collectedTables->hasTable($table)) {
			return $collectedTables;
		}

		$relatedCollectedTables = $collectedTables->filter(new DirectlyRelatedTo($this->schema, $table));

		if ($relatedCollectedTables->count() > 0) {
			$collectedTables->addTable($table);
		}

		return $collectedTables->reduce(new PathTo($this->schema, $collectedTables), $this->tables);
	}

}