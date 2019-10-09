<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\Expression;
use App\Reporting\DB\QueryBuilder\QueryParts\GroupBy;
use App\Reporting\DB\QueryBuilder\QueryParts\GroupByInterface;
use App\Reporting\DB\QueryBuilder\SqlExpressionInterface;

trait Groups
{
	protected $groupBys = [];

	protected $havings = [];

	public function groupBy($field)
	{
		$clone = clone $this;

		if (!$field instanceof SqlExpressionInterface) {
			$field = new Expression($field);
		}
		$clone->groupBys[] = $field;

		return $clone;
	}

	public function having($expression)
	{
		$clone = clone $this;

		if (!$expression instanceof SqlExpressionInterface) {
			$expression = new Expression($expression);
		}
		$clone->havings[] = $expression;

		return $clone;
	}

	protected function groupByStatementExpression()
	{
		return empty($this->groupBys) ? '' : ' GROUP BY '.implode(', ', $this->groupBys);
	}

	protected function havingStatementExpression()
	{
		return (empty($this->groupBys) || empty($this->havings)) ? '' : ' HAVING '.implode(', ', $this->havings);
	}

	protected function getGroupByParameters()
	{
		$parameters = [];

		foreach ($this->groupBys as $groupBy) {
			$parameters = $parameters + $groupBy->getParameters();
		}

		return $parameters;
	}

	protected function getHavingParameters()
	{
		$parameters = [];

		foreach ($this->havings as $having) {
			$parameters = array_merge($parameters, $having->getParameters());
		}

		return $parameters;
	}
}