<?php

namespace App\Reporting\Resources\Relationships;

use App\Reporting\Resources\RelationshipInterface;
use App\Reporting\Resources\Table;
use InvalidArgumentException;

class ManyToOne implements RelationshipInterface
{
	/** @var Table  */
	private $child;

	/** @var Table  */
	private $parent;

	private $condition;

	/**
	 * ManyToOne constructor.
	 * @param Table $child
	 * @param Table $parent
	 * @param $condition
	 */
	public function __construct(Table $child, Table $parent, $condition)
	{
		$this->child = $child;
		$this->parent = $parent;
		$this->condition = $condition;
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
			return $tableAlias === $this->child;
		}

		throw new InvalidArgumentException("`$tableAlias` and `$otherTableAlias` do not belong to this relationship.");
	}
}