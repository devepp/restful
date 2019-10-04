<?php

namespace App\Reporting\DB\QueryBuilder;

interface SelectQueryBuilderInterface extends QueryBuilderInterface
{
	/**
	 * @param mixed ...$fieldExpressions
	 * @return SelectQueryBuilderInterface
	 */
	public function select(...$fieldExpressions);

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @param $alias
	 * @return SelectQueryBuilderInterface
	 */
	public function selectSubQuery(SelectQueryBuilderInterface $queryBuilder, $alias);

	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return SelectQueryBuilderInterface
	 */
	public function where($field, $operator, $value);

	/**
	 * @param $whereString
	 * @return SelectQueryBuilderInterface
	 */
	public function whereRaw($whereString);

	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return SelectQueryBuilderInterface
	 */
	public function orWhere($field, $operator, $value);

	/**
	 * @param $field
	 * @param $values
	 * @return SelectQueryBuilderInterface
	 */
	public function whereIn($field, $values);

	/**
	 * @param $field
	 * @param $values
	 * @return SelectQueryBuilderInterface
	 */
	public function whereNotIn($field, $values);

	/**
	 * @param $field
	 * @return SelectQueryBuilderInterface
	 */
	public function whereNull($field);

	/**
	 * @param $field
	 * @return SelectQueryBuilderInterface
	 */
	public function whereNotNull($field);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @param $table
	 * @param $on
	 * @param string $type
	 * @return SelectQueryBuilderInterface
	 */
	public function join($table, $on, $type = 'inner');

	/**
	 * @param SelectQueryBuilderInterface $subQuery
	 * @param $alias
	 * @param $on
	 * @param string $type
	 * @return SelectQueryBuilderInterface
	 */
	public function joinSubQuery(SelectQueryBuilderInterface $subQuery, $alias, $on, $type = 'inner');

	/**
	 * @param $field
	 * @return SelectQueryBuilderInterface
	 */
	public function groupBy($field);

	/**
	 * @param $field
	 * @param string $direction
	 * @return SelectQueryBuilderInterface
	 */
	public function orderBy($field, $direction = 'ASC');

	/**
	 * @param $limit
	 * @param null $offset
	 * @return SelectQueryBuilderInterface
	 */
	public function limit($limit, $offset = null);

	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface
	 */
	public function subQuery($tableExpression);
}