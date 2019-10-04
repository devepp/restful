<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\OrderBy;
use App\Reporting\DB\QueryBuilder\QueryParts\OrderByInterface;

trait Orders
{
	/** @var OrderByInterface[] */
	protected $orderBys = [];


	public function orderBy($field, $direction = 'ASC')
	{
		$clone = clone $this;

		$clone->orderBys[] = new OrderBy($field, $direction);

		return $clone;
	}

	protected function orderByStatementExpression()
	{
		return empty($this->orderBys) ? '' : ' ORDER BY '.implode(', ', $this->orderBys);
	}

	protected function getOrderByParameters()
	{
		$parameters = [];

		foreach ($this->joins as $join) {
			$parameters = array_merge($parameters, $join->getParameters());
		}

		return $parameters;
	}
}