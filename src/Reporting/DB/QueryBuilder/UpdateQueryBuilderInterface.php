<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\QueryParts\Expression;

interface UpdateQueryBuilderInterface extends QueryBuilderInterface, WhereBuilderInterface, JoinsInterface, SetsValuesInterface
{

	/**
	 * @param $field
	 * @param string $direction
	 * @return UpdateQueryBuilderInterface
	 */
	public function orderBy($field, $direction = 'ASC');

	/**
	 * @param $limit
	 * @return UpdateQueryBuilderInterface
	 */
	public function limit($limit);

	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface
	 */
	public function subQuery($tableExpression);

	/**
	 * @param $expressionString
	 * @param array $parameters
	 * @return Expression
	 */
	public function expression($expressionString, $parameters = []);
}