<?php


namespace App\Reporting\Resources;


use App\Reporting\Filters\Filter;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\ReportField;

class ResourceBuilder
{
	/** @var Schema */
	private $schema;

	/** @var Table */
	private $baseResource;

	/** @var TableList */
	private $tables;

	/** @var ReportField[] */
	private $fields;

	/** @var Filter[] */
	private $filters;

	/** @var QueryGroup[] */
	private $queryGroups;

	/**
	 * ResourceBuilder constructor.
	 * @param Schema $schema
	 * @param Table $baseResource
	 */
	public function __construct(Schema $schema, $baseResourceAlias)
	{
		$this->schema = $schema;
		$this->baseResource = $this->schema->getTable($baseResourceAlias);
		$this->tables = new TableList();
	}

	public function build()
	{
		return new Resource($this->getQueryGroups(), $this->fields, $this->filters);
	}

	public function withTable($tableAlias)
	{
		$this->tables->addTable($this->schema->getTable($tableAlias));
	}

	/**
	 * @return QueryGroup[]
	 */
	private function getQueryGroups()
	{
		if (is_null($this->queryGroups)) {
			$this->compileQueryGroups();
		}

		return $this->queryGroups;
	}

	private function compileQueryGroups()
	{
		$root = $this->baseResource;
		$otherTables = $this->tables->getTables();
		$subQueryGroups = $this->getSubQueryGroups();

		return new QueryGroup($root, $otherTables, $subQueryGroups);

//		$first = true;
//		foreach ($this->tables as $table) {
//			if ($first) {
//				$first = false;
//			}
//			$this->addTable($table);
//		}
	}

	private function getOtherTables(Table $root, $otherTables = [])
	{
		$otherTables = [];

		foreach ($this->tables as $table) {
			foreach ($otherTables as $otherTable) {
				if ($this->schema->hasRelationship($table, $otherTable)) {
					$relationship = $this->schema->getRelationship($table, $otherTable);
					if ($relationship->tableHasOne($table, $otherTable)) {
						return $table;
					}
				}
			}
		}
	}

	private function getAnotherTable(Table $root, $otherTables = [])
	{
		$otherTables = [];

		foreach ($this->tables as $table) {
			foreach ($otherTables as $otherTable) {
				if ($this->schema->hasRelationship($table, $otherTable)) {
					return $table;
				}
			}
		}
	}




	private function addTable(Table $table)
	{
		$relation_table = $this->findFirstMatching($table->getRelationshipAliases());
//		var_dump($relation_table);
		if ($relation_table) {
			$is_child = $table->connectTable($relation_table);

			if ($is_child) {
				$this->query_groups[] = new QueryGroup($table);
			} else {
				$query_group = $this->findQueryGroupByTable($relation_table);
				$query_group->addTable($table);
			}
//			var_dump($table);
			return;
		}

		throw new \LogicException('Could not find path of required table(s) for '.$table->alias());
	}
}