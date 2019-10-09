<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\QueryParts\SubQuery;
use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\Traits\ConstrainsWithWheres;
use App\Reporting\DB\QueryBuilder\Traits\Groups;
use App\Reporting\DB\QueryBuilder\Traits\Joins;
use App\Reporting\DB\QueryBuilder\Traits\Limits;
use App\Reporting\DB\QueryBuilder\Traits\Orders;
use InvalidArgumentException;

class Select extends QueryBuilder implements SelectQueryBuilderInterface
{
	use Joins, ConstrainsWithWheres, Groups, Orders, Limits;

	protected $select = [];

	/** @var SqlExpressionInterface */
	protected $from;

	/**
	 * QueryBuilderInterface constructor.
	 * @param $tableExpression
	 */
	public function __construct($tableExpression)
	{
		$this->from = $this->fromValue($tableExpression);
	}

	/**
	 * @param mixed ...$fieldExpressions
	 * @return SelectQueryBuilderInterface
	 */
	public function select(...$fieldExpressions)
	{
		$clone = clone $this;

		$clone->select = array_merge($this->select, $fieldExpressions);

		return $clone;
	}

	public function selectSubQuery(SelectQueryBuilderInterface $queryBuilder, $alias)
	{
		$clone = clone $this;

		$clone->select[] = new SubQuery($queryBuilder, $alias);

		return $clone;
	}

	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface
	 */
	public function subQuery($tableExpression)
	{
		return new self($tableExpression);
	}

	public function getStatementExpression()
	{
		$sql = $this->selectExpression();
		$sql .= $this->fromExpression();
		$sql .= $this->joinExpressions();
		$sql .= $this->whereStatementExpressions();
		$sql .= $this->groupByStatementExpression();
		$sql .= $this->havingStatementExpression();
		$sql .= $this->orderByStatementExpression();
		$sql .= $this->limitStatementExpression();

		return $sql;
	}

	public function getParameters()
	{
		$parameters = [];

		$parameters = array_merge($parameters, $this->getSelectParameters());
		$parameters = array_merge($parameters, $this->getFromParameters());
		$parameters = array_merge($parameters, $this->getJoinParameters());
		$parameters = array_merge($parameters, $this->getWhereParameters());
		$parameters = array_merge($parameters, $this->getGroupByParameters());
		$parameters = array_merge($parameters, $this->getHavingParameters());
		$parameters = array_merge($parameters, $this->getOrderByParameters());
		$parameters = array_merge($parameters, $this->getLimitParameters());

		return $parameters;
	}

	private function selectExpression()
	{
		return empty($this->select) ? 'SELECT *' : 'SELECT '.implode(', ', $this->select);
	}

	private function fromExpression()
	{
		return ' FROM '.$this->from;
	}

	private function fromValue($tableExpression)
	{
		if(!$tableExpression instanceof SqlExpressionInterface && !is_string($tableExpression)) {
			throw new InvalidArgumentException('$tableExpression must be either a string or implement SqlExpressionInterface');
		}

		if (is_string($tableExpression)) {
			return TableExpression::fromString($tableExpression);
		}

		return $tableExpression;
	}

	private function getSelectParameters()
	{
		$parameters = [];

		foreach ($this->select as $selectColumn) {
			if ($selectColumn instanceof SqlExpressionInterface) {
				$parameters = array_merge($parameters, $selectColumn->getParameters());
			}
		}

		return $parameters;
	}

	private function getFromParameters()
	{
		return $this->from->getParameters();
	}


}