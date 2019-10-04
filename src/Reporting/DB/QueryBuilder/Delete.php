<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\QueryTypes\Type;
use App\Reporting\DB\QueryBuilder\Traits\ConstrainsWithWheres;
use App\Reporting\DB\QueryBuilder\Traits\Joins;
use App\Reporting\DB\QueryBuilder\Traits\Limits;
use App\Reporting\DB\QueryBuilder\Traits\Orders;

class Delete extends QueryBuilder implements DeleteQueryBuilderInterface
{
	use Joins, ConstrainsWithWheres, Orders, Limits;

	/** @var TableExpression */
	protected $from;

	protected $parameters = [];

	/**
	 * QueryBuilderInterface constructor.
	 * @param $tableExpression
	 * @param Type|null $type
	 */
	public function __construct($tableExpression)
	{
		$this->from = new TableExpression($tableExpression);
	}

	public function limit($limit)
	{
		$this->limit = $limit;
	}

	public function getStatementExpression()
	{
		$sql = 'DELETE '.$this->from;
		$sql .= ' FROM '.$this->from;
		$sql .= $this->joinExpressions();
		$sql .= $this->whereStatementExpressions();
		$sql .= $this->orderByStatementExpression();
		$sql .= $this->limitStatementExpression();

		return $sql;
	}

	public function getParameters()
	{
		$parameters = [];

		$parameters = $parameters + $this->getJoinParameters();
		$parameters = $parameters + $this->getWhereParameters();
		$parameters = $parameters + $this->getOrderByParameters();
		$parameters = $parameters + $this->getLimitParameters();

		return $parameters;
	}

	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface
	 */
	public function subQuery($tableExpression)
	{
		return new Select($tableExpression);
	}


}