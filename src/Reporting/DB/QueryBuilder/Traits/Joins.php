<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\Join;
use App\Reporting\DB\QueryBuilder\QueryParts\JoinInterface;
use App\Reporting\DB\QueryBuilder\QueryParts\SubQueryJoin;
use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\QueryParts\TableJoin;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

trait Joins
{
	/** @var JoinInterface[] */
	protected $joins = [];

	public function join($table, $on, $type = 'INNER')
	{
		$clone = clone $this;

		$tableExpression = TableExpression::fromString($table);

		$clone->joins[] = new TableJoin($tableExpression, $on, $type);

		return $clone;
	}

	public function joinSubQuery(SelectQueryBuilderInterface $subQuery, $alias, $on, $type = 'INNER')
	{
		$clone = clone $this;

		$clone->joins[] = new SubQueryJoin($subQuery, $alias, $on, $type);

		return $clone;
	}

	protected function joinExpressions()
	{
		return empty($this->joins) ? '' : ' '.implode(' ', $this->joins);
	}

	protected function getJoinParameters()
	{
		$parameters = [];

		foreach ($this->joins as $join) {
			$parameters = array_merge($parameters, $join->getParameters());
		}

		return $parameters;
	}
}