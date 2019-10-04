<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\SqlExpressionInterface;

class SubQuery implements SqlExpressionInterface
{
	/** @var SelectQueryBuilderInterface */
	private $subQuery;

	private $alias;

	/**
	 * SubQuery constructor.
	 * @param SelectQueryBuilderInterface $subQuery
	 * @param $alias
	 */
	public function __construct(SelectQueryBuilderInterface $subQuery, $alias)
	{
		$this->subQuery = $subQuery;
		$this->alias = $alias;
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}

	public function getStatementExpression()
	{
		return '('.$this->subQuery->getStatementExpression().') '.$this->alias;
	}

	public function getParameters()
	{
		return $this->subQuery->getParameters();
	}

}