<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\Expression;

trait MakesExpressions
{
	public function expression($expressionString, $parameters = [])
	{
		return new Expression($expressionString, $parameters);
	}
}