<?php

namespace App\Reporting\Resources;

use App\Reporting\Processing\QueryGroup;

class Schema
{
	/** @var TableList */
	private $tables;

	/** @var RelationshipInterface[]  */
	private $relationships = [];

	public static function builder()
	{
		return new SchemaBuilder();
	}

	/**
	 * Schema constructor.
	 * @param TableList $tables
	 * @param RelationshipInterface[] $relationships
	 */
	public function __construct(TableList $tables, $relationships)
	{
		$this->tables = $tables;
		$this->relationships = $relationships;
	}

	public function getTable($tableAlias)
	{
		return $this->tables->getTable($tableAlias);
	}

	/**
	 * @param $tableAlias
	 * @param $otherTableAlias
	 * @return bool
	 */
	public function hasRelationship($tableAlias, $otherTableAlias)
	{
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
		foreach ($this->relationships as $relationship) {
			if ($relationship->hasTables($tableAlias, $otherTableAlias)) {
				return $relationship;
			}
		}

		throw new \LogicException("Relationship does not exist for tables: `$tableAlias` and `$otherTableAlias`");
	}


//	public function queryGroups($primaryTableAlias, $otherTableAliases)
//	{
//		$primaryTable = $this->getTable($primaryTableAlias);
//		$mainQueryGroup = new QueryGroup($primaryTable);
//
//		foreach ($otherTableAliases as $tableAlias) {
//			$table = $this->getTable($tableAlias);
//			foreach ($this->relationships as $relationship)
//			$mainQueryGroup->addTable($table);
//		}
//	}
}