<?php

namespace App\Reporting\Resources;

use App\Reporting\Processing\QueryGroup;
use App\Reporting\Resources\TableCollectionFunctions\Filters\DirectlyRelatedTo;
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
	public function getTables($tableAliases)
	{
		$tables = new TableCollection([]);
		foreach ($tableAliases as $tableAlias) {
			$tables->addTable($this->getTable($tableAlias));
		}

		return $tables;
	}

	/**
	 * @param $tableAlias
	 * @param $otherTableAlias
	 * @return bool
	 */
	public function hasDirectRelationship($tableAlias, $otherTableAlias)
	{
		if ($tableAlias === $otherTableAlias) {
			return false;
		}
		foreach ($this->relationships as $relationship) {
			if ($relationship->hasTables($tableAlias, $otherTableAlias)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $tableAlias
	 * @param $otherTableAlias
	 * @return RelationshipInterface
	 */
	public function getRelationship($tableAlias, $otherTableAlias)
	{
		if ($tableAlias !== $otherTableAlias) {
			foreach ($this->relationships as $relationship) {
				if ($relationship->hasTables($tableAlias, $otherTableAlias)) {
					return $relationship;
				}
			}
		}

		throw new \LogicException("Relationship does not exist for tables: `$tableAlias` and `$otherTableAlias`");
	}

	/**
	 * @param $tableAlias
	 * @param $otherTableAlias
	 * @return mixed|null
	 */
	public function hasRelationship($tableAlias, $otherTableAlias)
	{
		$path = $this->getPath($tableAlias, $otherTableAlias);

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
		$path = $this->getPath($tableAlias, $otherTableAlias);

		if ($path) {
			return $path;
		}

		throw new \LogicException("Relationship does not exist for tables: `$tableAlias` and `$otherTableAlias`");
	}

	private function getPath($tableAlias, $otherTableAlias, $pathSoFar = [])
	{
		if (empty($pathSoFar)) {
			$pathSoFar[] = $tableAlias;
		}

		if ($tableAlias === $otherTableAlias) {
			return $pathSoFar;
		}

		$currentTable = $this->tables->getTable($tableAlias);
		$relatedTables = $this->tables->filter(new DirectlyRelatedTo($currentTable, $this));
		$possiblePaths = [];

		foreach ($relatedTables as $relatedTable) {
			if (!in_array($relatedTable->alias(), $pathSoFar)) {
				$newPathSoFar = $pathSoFar;
				$newPathSoFar[] = $relatedTable->alias();
				$path = $this->getPath($relatedTable->alias(), $otherTableAlias, $newPathSoFar);
				if ($path) {
					$possiblePaths[] = $path;
				}
			}
		}

		$shortestPath = null;
		foreach ($possiblePaths as $possiblePath) {
			if ($shortestPath === null) {
				$shortestPath = $possiblePath;
			}
			$shortestPath = (count($shortestPath) < count($possiblePath)) ? $shortestPath : $possiblePath;
		}

		return $shortestPath;
	}

	private function getRelationships($tableAlias)
	{

		foreach ($this->relationships as $relationship) {
			if ($relationship->hasTable($tableAlias)) {
				$relationship;

			}
		}
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
		$tablesInMainNode = $tables->filter(new SameNodeAs($root, $this));

		$remainingTables = $tables->filter(new Exclude($tablesInMainNode));

		$subQueryGroups = [];

		$remainingTablesCount = $remainingTables->count();

		while ($remainingTablesCount > 0) {

			$newNodeRoot = $remainingTables->current();

			$path = $this->getRelationshipPath($root->alias(), $newNodeRoot->alias());
			$pathTables = $this->getTables($path);

			$remainingTables = $pathTables->merge($remainingTables);

			$tablesInThisNode = $remainingTables->filter(new SameNodeAs($newNodeRoot, $this));

			$remainingTables = $remainingTables->filter(new Exclude($tablesInThisNode));

			$subQueryGroups[] = new QueryGroup($newNodeRoot, $tablesInThisNode);

			$remainingTablesCount = $remainingTables->count();
		}

		return new QueryGroup($root, $tablesInMainNode, $subQueryGroups);
	}
}