<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

class WhereExists implements WhereInterface
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
		return 'EXISTS('.$this->selectBuilder->getStatementExpression().')';
	}

	public function getParameters()
	{
		return $this->selectBuilder->getParameters();
	}

}