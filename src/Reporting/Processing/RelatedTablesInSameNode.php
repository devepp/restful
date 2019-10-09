<?php

namespace App\Reporting\Processing;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableList;

class RelatedTablesInSameNode
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

	public function __invoke(TableList $collectedTables, Table $table)
	{
		
	}
}