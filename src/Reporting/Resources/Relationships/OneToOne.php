<?php

namespace App\Reporting\Resources\Relationships;

use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\Table;
use InvalidArgumentException;

class OneToOne implements RelationshipInterface
{
	/** @var Table */
	private $table;

	/** @var Table */
	private $otherTable;

	private $condition;

	/**
	 * OneToOne constructor.
	 * @param Table $table
	 * @param Table $otherTable
	 * @param $condition
	 */
	public function __construct(Table $table, Table $otherTable, $condition)
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


}