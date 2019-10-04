<?php


namespace App\Reporting\Resources;


use App\Reporting\Processing\QueryGroup;

class ResourceBuilder
{
	/** @var Table */
	private $baseResource;

	/** @var TableList */
	private $tables;

	/** @var QueryGroup[] */
	private $queryGroups;

	/**
	 * ResourceBuilder constructor.
	 * @param Table $baseResource
	 */
	public function __construct(Table $baseResource)
	{
		$this->baseResource = $baseResource;
		$this->tables = new TableList();
	}

	public function build()
	{
		return new Resource($this->getQueryGroups());
	}

	public function withTable(Table $table)
	{
		$this->tables->addTable($table);
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
		$first = true;
		foreach ($this->tables as $table) {
			if ($first) {
				$first = false;
			}
			$this->addTable($table);
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