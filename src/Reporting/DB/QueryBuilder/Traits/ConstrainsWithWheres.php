<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\Expression;
use App\Reporting\DB\QueryBuilder\QueryParts\Where;
use App\Reporting\DB\QueryBuilder\QueryParts\WhereExists;
use App\Reporting\DB\QueryBuilder\QueryParts\WhereIn;
use App\Reporting\DB\QueryBuilder\QueryParts\WhereInterface;
use App\Reporting\DB\QueryBuilder\QueryParts\WhereNotExists;
use App\Reporting\DB\QueryBuilder\QueryParts\WhereNotIn;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\SqlExpressionInterface;

trait ConstrainsWithWheres
{
	/** @var WhereInterface[]  */
	protected $wheres = [];

	public function where($field, $operator, $value)
	{
		$clone = clone $this;

		$clone->addWhere(new Where($field, $operator, $value));

		return $clone;
	}

	public function whereRaw($whereString)
	{
		$clone = clone $this;

		$clone->addWhere($whereString);

		return $clone;
	}

	public function orWhere($field, $operator, $value)
	{
		$clone = clone $this;

		$clone->addWhere(new Where($field, $operator, $value), 'OR');

		return $clone;
	}

	public function whereIn($field, $values)
	{
		$clone = clone $this;

		$clone->addWhere(new WhereIn($field, $values));

		return $clone;
	}

	public function whereNotIn($field, $values)
	{
		$clone = clone $this;

		$clone->addWhere(new WhereNotIn($field, $values));

		return $clone;
	}

	public function whereNull($field)
	{
		$clone = clone $this;

		$clone->addWhere($field.' IS NULL');

		return $clone;
	}

	public function whereNotNull($field)
	{
		$clone = clone $this;

		$clone->addWhere($field.' IS NOT NULL');

		return $clone;
	}

	public function whereBetween($field, $low, $high)
	{
		$clone = clone $this;

		$clone->addWhere(new Expression($field.' BETWEEN ? AND ?', [$low, $high]));

		return $clone;
	}

	public function whereNotBetween($field, $low, $high)
	{
		$clone = clone $this;

		$clone->addWhere(new Expression($field.' NOT BETWEEN ? AND ?', [$low, $high]));

		return $clone;
	}

	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		$clone = clone $this;

		$clone->addWhere(new WhereExists($selectQueryBuilder));

		return $clone;
	}

	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		$clone = clone $this;

		$clone->addWhere(new WhereNotExists($selectQueryBuilder));

		return $clone;
	}

	protected function whereStatementExpressions()
	{
		return empty($this->wheres)? '' :  ' WHERE '.$this->whereExpressions();
	}

	protected function whereExpressions()
	{
		return implode(' ', $this->wheres);
	}

	protected function getWhereParameters()
	{
		$parameters = [];

		foreach ($this->wheres as $where) {
			if ($where instanceof SqlExpressionInterface) {
				$parameters = array_merge($parameters, $where->getParameters());
			}
		}

		return $parameters;
	}

	protected function addWhere($where, $operand = 'AND')
	{
		if (!$where instanceof SqlExpressionInterface && is_string($where)) {
			$where = new Expression($where);
		}

		if (!empty($this->wheres)) {
			$this->wheres[] = $operand;
		}

		$this->wheres[] = $where;
	}
}