<?php

namespace App\Reporting\Resources;

use App\Reporting\Processing\QueryGroup;
use App\Reporting\Resources\TableCollectionFunctions\Filters\Exclude;
use App\Reporting\Resources\TableCollectionFunctions\Filters\SameNodeAs;

class Schema
{
	/** @var TableCollection */
	private $tables;

	/** @var RelationshipInterface[]  */
	private $relationships = [];

	public static function builder()
	{
		return new SchemaBuilder();
	}

	/**
	 * Schema constructor.
	 * @param TableCollection $tables
	 * @param RelationshipInterface[] $relationships
	 */
	public function __construct(TableCollection $tables, $relationships)
	{
		$this->tables = $tables;
		$this->relationships = $relationships;
	}

	public function getTable($tableAlias)
	{
		return $this->tables->getTable($tableAlias);
	}

	/**
	 * @param string[] $tableAliases
	 * @return TableCollection
	 */
	public function getTables($tableAliases = null)
	{
		$tables = new TableCollection([]);
		foreach ($tableAliases as $tableAlias) {
			$tables = $tables->addTable($this->getTable($tableAlias));
		}

		return $tables;
	}

	/**
	 * @return TableCollection
	 */
	public function getAllTables()
	{
		return $this->tables;
	}

	/**
	 * @param $tableAlias
	 * @param $otherTableAlias
	 * @return mixed|null
	 */
	public function hasRelationship($tableAlias, $otherTableAlias)
	{
		$fromTable = $this->tables->getTable($tableAlias);
		$toTable = $this->tables->getTable($otherTableAlias);

		$path = $fromTable->pathTo($toTable, $this->tables);

		if ($path) {
			return true;
		}

		return false;
	}

	/**
	 * @param $tableAlias
	 * @param $otherTableAlias
	 * @return mixed|null
	 */
	public function getRelationshipPath($tableAlias, $otherTableAlias)
	{
		$fromTable = $this->tables->getTable($tableAlias);
		$toTable = $this->tables->getTable($otherTableAlias);

		$path = $fromTable->pathTo($toTable, $this->tables);

		if ($path) {
			return $path->aliases();
		}

		throw new \LogicException("Relationship does not exist for tables: `$tableAlias` and `$otherTableAlias`");
	}

	/**
	 * This could possibly be turned into a TableCollection reducer
	 *
	 * @param Table $root
	 * @param TableCollection $tables
	 * @return QueryGroup
	 */
	public function getQueryGroup(Table $root, TableCollection $tables)
	{
//		die(var_dump($tables->map(function (Table $table){ return $table->alias(); })));
		$tablesInMainNode = $tables->filter(new SameNodeAs($root, $this->tables));

		$remainingTables = $tables->filter(new Exclude($tablesInMainNode));

		$subQueryGroups = [];

		$remainingTablesCount = $remainingTables->count();

		while ($remainingTablesCount > 0) {

			$newNodeRoot = $remainingTables->current();

			$pathTables = $root->pathTo($newNodeRoot, $this->tables);

			$tablesInThisNode = $remainingTables->filter(new SameNodeAs($newNodeRoot, $this->tables));

			$subQueryGroups[] = new QueryGroup($newNodeRoot, $tablesInThisNode, $pathTables, []);

			$remainingTables = $remainingTables->filter(new Exclude($tablesInThisNode));
			$remainingTablesCount = $remainingTables->count();
		}

		return new QueryGroup($root, $tablesInMainNode, TableCollection::fromArray([$root]), $subQueryGroups);
	}
}