<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\GroupBy;
use App\Reporting\DB\QueryBuilder\QueryParts\GroupByInterface;

trait Groups
{
	protected $groupBys = [];

	protected $havings = [];


	public function groupBy($field)
	{
		$clone = clone $this;

		$clone->groupBys = [$field];

		return $clone;
	}

	public function having($aggregate, $operator, $value)
	{
		$clone = clone $this;

		$clone->havings = [$aggregate];

		return $clone;
	}

	public function addHaving($aggregate, $operator, $value)
	{
		$clone = clone $this;

		$clone->groupBys[] = $aggregate;

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

		foreach ($this->joins as $join) {
			$parameters = $parameters + $join->getParameters();
		}

		return $parameters;
	}

	protected function getHavingParameters()
	{
		$parameters = [];

		foreach ($this->joins as $join) {
			$parameters = array_merge($parameters, $join->getParameters());
		}

		return $parameters;
	}
}