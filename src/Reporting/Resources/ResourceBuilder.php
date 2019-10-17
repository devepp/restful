<?php

namespace App\Reporting\Resources;

use App\Reporting\Filters\Filter;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\ReportField;
use App\Reporting\Resources\TableCollectionFunctions\Filters\Exclude;
use App\Reporting\Resources\TableCollectionFunctions\Filters\SameNodeAs;

class ResourceBuilder
{
	/** @var Schema */
	private $schema;

	/** @var Table */
	private $baseResource;

	/** @var TableCollection */
	private $tables;

	/** @var ReportField[] */
	private $fields;

	/** @var Filter[] */
	private $filters;

	/**
	 * ResourceBuilder constructor.
	 * @param Schema $schema
	 * @param $baseResourceAlias
	 */
	public function __construct(Schema $schema, $baseResourceAlias)
	{
		$this->schema = $schema;
		$this->baseResource = $this->schema->getTable($baseResourceAlias);
		$this->tables = new TableCollection([$baseResourceAlias]);
	}

	public function build()
	{
		return new Resource($this->compileQueryGroups(), $this->fields, $this->filters);
	}

	public function withTable($tableAlias)
	{
		if ($this->tables->findFirstMatching([$tableAlias])) {
			$this->tables->addTable($this->schema->getTable($tableAlias));
		}
	}

	private function compileQueryGroups()
	{
		return $this->getQueryGroup($this->baseResource, $this->tables, $this->schema, []);
	}

	/**
	 * This could possibly be turned into a TableCollection reducer
	 *
	 * @param Table $root
	 * @param TableCollection $tables
	 * @param Schema $schema
	 * @param array $subQueryGroups
	 * @return QueryGroup
	 */
	private function getQueryGroup(Table $root, TableCollection $tables, Schema $schema, $subQueryGroups = [])
	{
		$otherTables = $tables->filter(new SameNodeAs($root, $this->schema));

		$leftOverTables = $tables->filter(new Exclude($otherTables));

		if ($leftOverTables->count() > 0) {
			$subQueryGroups = $this->getQueryGroup($leftOverTables->current(), $leftOverTables, $schema, $subQueryGroups);
		}

		return new QueryGroup($root, $otherTables, $subQueryGroups);
	}
}