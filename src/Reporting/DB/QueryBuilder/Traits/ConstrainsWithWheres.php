<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

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

		$clone->wheres = [new Where($field, $operator, $value)];

		return $clone;
	}

	public function whereRaw($whereString)
	{
		$clone = clone $this;

		$clone->wheres = [$whereString];

		return $clone;
	}

	public function orWhere($field, $operator, $value)
	{
		$clone = clone $this;

		$clone->wheres[] = new Where($field, $operator, $value);

		return $clone;
	}

	public function whereIn($field, $values)
	{
		$clone = clone $this;

		$clone->wheres[] = new WhereIn($field, $values);

		return $clone;
	}

	public function whereNotIn($field, $values)
	{
		$clone = clone $this;

		$clone->wheres[] = new WhereNotIn($field, $values);

		return $clone;
	}

	public function whereNull($field)
	{
		$clone = clone $this;

		$clone->wheres[] = $field.' IS NULL';

		return $clone;
	}

	public function whereNotNull($field)
	{
		$clone = clone $this;

		$clone->wheres[] = $field.' IS NOT NULL';

		return $clone;
	}

	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		$clone = clone $this;

		$clone->wheres[] = new WhereExists($selectQueryBuilder);

		return $clone;
	}

	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		$clone = clone $this;

		$clone->wheres[] = new WhereNotExists($selectQueryBuilder);

		return $clone;
	}

	protected function whereStatementExpressions()
	{
		return empty($this->wheres)? '' :  ' WHERE '.$this->whereExpressions();
	}

	protected function whereExpressions()
	{
		return empty($this->wheres)? '' :  implode(' ', $this->wheres);
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
}