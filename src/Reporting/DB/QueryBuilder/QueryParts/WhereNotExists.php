<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class WhereNotExists implements WhereInterface
{
	/** @var SelectQueryBuilderInterface */
	private $selectBuilder;

	/**
	 * WhereExists constructor.
	 * @param SelectQueryBuilderInterface $selectBuilder
	 */
	public function __construct(SelectQueryBuilderInterface $selectBuilder)
	{
		$this->selectBuilder = $selectBuilder;
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}

	public function getStatementExpression()
	{
		return 'NOT EXISTS('.$this->selectBuilder->getStatementExpression().')';
	}

	public function getParameters()
	{
		return $this->selectBuilder->getParameters();
	}

}