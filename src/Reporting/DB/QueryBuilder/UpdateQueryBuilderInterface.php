<?php

namespace App\Reporting\DB\QueryBuilder;

interface UpdateQueryBuilderInterface extends QueryBuilderInterface
{
	/**
	 * @param $fieldName
	 * @param $value
	 * @return UpdateQueryBuilderInterface
	 */
	public function setValue($fieldName, $value);

	/**
	 * @param $values
	 * @return UpdateQueryBuilderInterface
	 */
	public function setValues($values);

	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return UpdateQueryBuilderInterface
	 */
	public function where($field, $operator, $value);

	/**
	 * @param $whereString
	 * @return UpdateQueryBuilderInterface
	 */
	public function whereRaw($whereString);

	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return UpdateQueryBuilderInterface
	 */
	public function orWhere($field, $operator, $value);

	/**
	 * @param $field
	 * @param $values
	 * @return UpdateQueryBuilderInterface
	 */
	public function whereIn($field, $values);

	/**
	 * @param $field
	 * @param $values
	 * @return UpdateQueryBuilderInterface
	 */
	public function whereNotIn($field, $values);

	/**
	 * @param $field
	 * @return UpdateQueryBuilderInterface
	 */
	public function whereNull($field);

	/**
	 * @param $field
	 * @return UpdateQueryBuilderInterface
	 */
	public function whereNotNull($field);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return UpdateQueryBuilderInterface
	 */
	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return UpdateQueryBuilderInterface
	 */
	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @param $table
	 * @param $on
	 * @param string $type
	 * @return UpdateQueryBuilderInterface
	 */
	public function join($table, $on, $type = 'inner');

	/**
	 * @param SelectQueryBuilderInterface $subQuery
	 * @param $alias
	 * @param $on
	 * @param string $type
	 * @return UpdateQueryBuilderInterface
	 */
	public function joinSubQuery(SelectQueryBuilderInterface $subQuery, $alias, $on, $type = 'inner');

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
}