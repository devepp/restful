<?php

namespace App\Reporting\DB\QueryBuilder;

interface DeleteQueryBuilderInterface extends QueryBuilderInterface
{
	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return DeleteQueryBuilderInterface
	 */
	public function where($field, $operator, $value);

	/**
	 * @param $whereString
	 * @return DeleteQueryBuilderInterface
	 */
	public function whereRaw($whereString);

	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return DeleteQueryBuilderInterface
	 */
	public function orWhere($field, $operator, $value);

	/**
	 * @param $field
	 * @param $values
	 * @return DeleteQueryBuilderInterface
	 */
	public function whereIn($field, $values);

	/**
	 * @param $field
	 * @param $values
	 * @return DeleteQueryBuilderInterface
	 */
	public function whereNotIn($field, $values);

	/**
	 * @param $field
	 * @return DeleteQueryBuilderInterface
	 */
	public function whereNull($field);

	/**
	 * @param $field
	 * @return DeleteQueryBuilderInterface
	 */
	public function whereNotNull($field);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return DeleteQueryBuilderInterface
	 */
	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return DeleteQueryBuilderInterface
	 */
	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @param $table
	 * @param $on
	 * @param string $type
	 * @return DeleteQueryBuilderInterface
	 */
	public function join($table, $on, $type = 'inner');

	/**
	 * @param SelectQueryBuilderInterface $subQuery
	 * @param $alias
	 * @param $on
	 * @param string $type
	 * @return DeleteQueryBuilderInterface
	 */
	public function joinSubQuery(SelectQueryBuilderInterface $subQuery, $alias, $on, $type = 'inner');

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