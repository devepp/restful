<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\Common\StringableInterface;

interface SqlExpressionInterface extends StringableInterface
{
	public function getStatementExpression();
	public function getParameters();
}