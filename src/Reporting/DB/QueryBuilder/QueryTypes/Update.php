<?php

namespace App\Reporting\DB\QueryBuilder\QueryTypes;


use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;

class Update extends Type
{
	public function type()
	{
		return Type::UPDATE;
	}

	public function compileStatement(TableExpression $tableExpression, $selectFields = [], $joinExpressions = [], $whereExpressions = [])
	{
		return 'UPDATE '.$tableExpression.' '.$tableExpression.' '.implode(' ', $joinExpressions).' SET '.' WHERE '.implode(' ', $whereExpressions);
	}


}