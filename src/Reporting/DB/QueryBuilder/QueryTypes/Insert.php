<?php

namespace App\Reporting\DB\QueryBuilder\QueryTypes;


use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;

class Insert extends Type
{
	public function type()
	{
		return Type::INSERT;
	}

	public function compileStatement(TableExpression $tableExpression, $selectFields = [], $joinExpressions = [], $whereExpressions = [])
	{
		return 'INSERT INTO '.$tableExpression->getTable().' VALUES ('.implode(', ', $selectFields).') ';
	}


}