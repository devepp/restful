<?php

namespace App\Reporting\DB\QueryBuilder;

interface InsertQueryBuilderInterface extends QueryBuilderInterface, SetsValuesInterface
{

	/**
	 * @param SelectQueryBuilderInterface $selectQuery
	 * @return InsertQueryBuilderInterface
	 */
	public function insertSubQuery(SelectQueryBuilderInterface $selectQuery);

	/**
	 * @param $expressionString
	 * @param array $parameters
	 * @return Expression
	 */
	public function expression($expressionString, $parameters = []);
}