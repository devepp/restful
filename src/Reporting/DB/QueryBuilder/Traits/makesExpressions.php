<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\Expression;

trait makesExpressions
{
	public function expression($expressionString, $parameters = [])
	{
		return new Expression($expressionString, $parameters);
	}
}