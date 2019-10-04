<?php

namespace App\Reporting\DB\QueryBuilder;

interface SqlExpressionInterface extends StringableInterface
{
	public function getStatementExpression();
	public function getParameters();
}