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
	public function compileStatement(TableExpression $tableExpression, $selectFields = [], $joinExpressions = [], $whereExpressions = [], $groupBys = [], $havings = [], $orderBys = [])
	{
		$fields = empty($selectFields) ? '*' : implode(', ', $selectFields);
		$joinClauses = empty($joinExpressions) ? '' : ' '.implode(' ', $joinExpressions);
		$whereClause = empty($whereExpressions) ? '' : ' WHERE '.implode(' ', $whereExpressions);
		$groupByClause = empty($groupBys) ? '' : ' GROUP BY '.implode(', ', $groupBys);
		$havingClause = empty($havings) ? '' : ' HAVING '.implode(' ', $havings);
		$orderByClause = empty($orderBys) ? '' : ' ORDER BY '.implode(', ', $orderBys);

		return 'SELECT '.$fields.' FROM '.$tableExpression.$joinClauses.$whereClause.$groupByClause.$havingClause.$orderByClause;
	}

}