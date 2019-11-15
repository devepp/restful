<?php

namespace App\Reporting\DB\QueryBuilder\Builders;

use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\Traits\ConstrainsWithWheres;
use App\Reporting\DB\QueryBuilder\Traits\Joins;
use App\Reporting\DB\QueryBuilder\Traits\Limits;
use App\Reporting\DB\QueryBuilder\Traits\MakesExpressions;
use App\Reporting\DB\QueryBuilder\Traits\MakesSubQueryBuilder;
use App\Reporting\DB\QueryBuilder\Traits\Orders;
use App\Reporting\DB\QueryBuilder\Traits\SetsValues;
use App\Reporting\DB\QueryBuilder\UpdateQueryBuilderInterface;

class Update extends QueryBuilder implements UpdateQueryBuilderInterface
{
	use ConstrainsWithWheres, Joins, SetsValues, Orders, Limits, MakesExpressions, MakesSubQueryBuilder;

	/** @var TableExpression */
	protected $updateTable;

	protected $parameters = [];

	/**
	 * QueryBuilderInterface constructor.
	 * @param $tableExpression
	 */
	public function __construct($tableExpression)
	{
		$this->updateTable = new TableExpression($tableExpression);
	}

	public function limit($limit)
	{
		$this->limit = $limit;
	}

	public function getStatementExpression()
	{
		$sql = 'UPDATE '.$this->updateTable;
		$sql .= $this->joinExpressions();
		$sql .= $this->whereStatementExpressions();
		$sql .= $this->setStatementExpression();
		$sql .= $this->orderByStatementExpression();
		$sql .= $this->limitStatementExpression();

		return $sql;
	}

	public function getParameters()
	{
		$parameters = [];

		$parameters = $parameters + $this->getJoinParameters();
		$parameters = $parameters + $this->getWhereParameters();
		$parameters = $parameters + $this->getSetParameters();
		$parameters = $parameters + $this->getOrderByParameters();
		$parameters = $parameters + $this->getLimitParameters();

		return $parameters;
	}
}