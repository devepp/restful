<?php

namespace App\Reporting\DB\QueryBuilder\Builders;

use App\Reporting\DB\Query;
use App\Reporting\DB\QueryBuilder\QueryBuilderInterface;

abstract class QueryBuilder implements QueryBuilderInterface
{
	abstract public function getStatementExpression();
	abstract public function getParameters();

	public function getQuery()
	{
		return new Query($this->getStatementExpression(), $this->getParameters());
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}
}