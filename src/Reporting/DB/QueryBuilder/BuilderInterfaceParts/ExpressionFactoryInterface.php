<?php

namespace App\Reporting\DB\QueryBuilder\BuilderInterfaceParts;

use App\Reporting\DB\QueryBuilder\SqlExpressionInterface;

interface ExpressionFactoryInterface
{
	/**
	 * @param $expressionString
	 * @param array $parameters
	 * @return SqlExpressionInterface
	 */
	public function expression($expressionString, $parameters = []);
}