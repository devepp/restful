<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\Traits\ConstrainsWithWheres;
use App\Reporting\DB\QueryBuilder\WhereBuilderInterface;

class WhereCollection implements WhereInterface, WhereBuilderInterface
{
	use ConstrainsWithWheres;

	public function getStatementExpression()
	{
		return empty($this->wheres)? '' :  '('.$this->whereExpressions().')';
	}

	public function getParameters()
	{
		return $this->getWhereParameters();
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}
}