<?php

namespace App\Reporting\Resources\Relationships;

use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\TableName;
use InvalidArgumentException;

class OneToOne implements RelationshipInterface
{
	/** @var TableName */
	private $table;

	/** @var TableName */
	private $otherTable;

	private $condition;

	/**
	 * OneToOne constructor.
	 * @param TableName $table
	 * @param TableName $otherTable
	 * @param $condition
	 */
	public function __construct(TableName $table, TableName $otherTable, $condition)
	{
		$this->table = $table;
		$this->otherTable = $otherTable;
		$this->condition = $condition;
	}

	public function hasTable($tableAlias)
	{
		return $this->table->alias() === $tableAlias || $this->otherTable->alias() === $tableAlias;
	}

	public function hasTables($tableAlias, $otherTableAlias)
	{
		return $this->hasTable($tableAlias) && $this->hasTable($otherTableAlias);
	}

	public function condition()
	{
		return $this->condition;
	}

	public function tableHasOne($tableAlias, $otherTableAlias)
	{
		if ($this->hasTables($tableAlias, $otherTableAlias)) {
			return true;
		}

		throw new InvalidArgumentException("`$tableAlias` and `$otherTableAlias` do not belong to this relationship.");
	}

	public function getOtherAlias($tableAlias)
	{
		if ($this->table->alias() === $tableAlias) {
			return $this->otherTable->alias();
		}
		if ($this->otherTable->alias() === $tableAlias) {
			return $this->table->alias();
		}

		throw new \LogicException("tableAlias $tableAlias is not part of this relationship");
	}


}