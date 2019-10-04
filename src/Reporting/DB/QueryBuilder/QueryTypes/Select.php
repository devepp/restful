<?php

namespace App\Reporting\DB\QueryBuilder\QueryTypes;


use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;

class Select extends Type
{
	public function type()
	{
		return Type::SELECT;
	}

	/**
	 * @param TableExpression $tableExpression
	 * @param array $selectFields
	 * @param array $joinExpressions
	 * @param array $whereExpressions
	 * @return string
	 */
	public function compileStatement(TableExpression $tableExpression, $selectFields = [], $joinExpressions = [], $whereExpressions = [])
	{
		$fields = empty($selectFields) ? '*' : implode(', ', $selectFields);
		return 'SELECT '.$fields.' FROM '.$tableExpression.' '.implode(' ', $joinExpressions).' WHERE '.implode(' ', $whereExpressions);
	}

}