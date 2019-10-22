<?php

namespace App\Reporting\Resources\Relationships;

use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\TableName;
use InvalidArgumentException;

class ManyToOne implements RelationshipInterface
{
	/** @var TableName  */
	private $child;

	/** @var TableName  */
	private $parent;

	private $condition;

	/**
	 * ManyToOne constructor.
	 * @param TableName $many
	 * @param TableName $one
	 * @param $condition
	 */
	public function __construct(TableName $many, TableName $one, $condition)
	{
		$this->child = $many;
		$this->parent = $one;
		$this->condition = $condition;
	}

	public function __debugInfo()
	{
		return [
			'child' => $this->child,
			'parent' => $this->parent,
			'condition' => $this->condition,
		];
	}

	public function hasTable($tableAlias)
	{
		return $this->child->alias() === $tableAlias || $this->parent->alias() === $tableAlias;
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
			return $tableAlias === $this->child->alias();
		}

		throw new InvalidArgumentException("`$tableAlias` and `$otherTableAlias` do not belong to this relationship.");
	}

	public function getOtherAlias($tableAlias)
	{
		if ($this->child->alias() === $tableAlias) {
			return $this->parent->alias();
		}
		if ($this->parent->alias() === $tableAlias) {
			return $this->child->alias();
		}

		throw new \LogicException("tableAlias $tableAlias is not part of this relationship");
	}
}