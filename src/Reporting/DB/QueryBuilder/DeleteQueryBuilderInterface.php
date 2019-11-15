<?php

namespace App\Reporting\DB\QueryBuilder;

interface DeleteQueryBuilderInterface extends QueryBuilderInterface, WhereBuilderInterface, JoinsInterface
{

	/**
	 * @param $field
	 * @param string $direction
	 * @return DeleteQueryBuilderInterface
	 */
	public function orderBy($field, $direction = 'ASC');

	/**
	 * @param $limit
	 * @return DeleteQueryBuilderInterface
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