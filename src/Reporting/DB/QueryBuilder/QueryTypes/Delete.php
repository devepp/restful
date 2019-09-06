<?php

namespace App\Reporting\DB\QueryBuilder\QueryTypes;


use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;

class Delete extends Type
{
	public function type()
	{
		return Type::DELETE;
	}

	public function compileStatement(TableExpression $tableExpression, $selectFields = [], $joinExpressions = [], $whereExpressions = [], $groupBys = [], $havings = [], $orderBys = [])
	{
		$alias = $tableExpression->getAlias() ? $tableExpression->getAlias() : $tableExpression->getTable();
		$from = empty($joinExpressions) ? ' FROM '.$tableExpression : $alias.' FROM '.$tableExpression.' '.implode(' ', $joinExpressions);
		return 'DELETE '.$from.' '.implode(' ', $whereExpressions);
	}


}