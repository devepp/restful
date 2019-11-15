<?php

namespace App\Reporting\DB\QueryBuilder\BuilderInterfaceParts;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

interface WhereBuilderInterface
{

	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return self - cloned builder
	 */
	public function where($field, $operator, $value);

	/**
	 * @param $whereString
	 * @return self - cloned builder
	 */
	public function whereRaw($whereString);

	/**
	 * @param $field
	 * @param $operator
	 * @param $value
	 * @return self - cloned builder
	 */
	public function orWhere($field, $operator, $value);

	/**
	 * @param $field
	 * @param $values
	 * @return self - cloned builder
	 */
	public function whereIn($field, $values);

	/**
	 * @param $field
	 * @param $values
	 * @return self - cloned builder
	 */
	public function whereNotIn($field, $values);

	/**
	 * @param $field
	 * @param $low
	 * @param $high
	 * @return self - cloned builder
	 */
	public function whereBetween($field, $low, $high);

	/**
	 * @param $field
	 * @param $low
	 * @param $high
	 * @return self - cloned builder
	 */
	public function whereNotBetween($field, $low, $high);

	/**
	 * @param $field
	 * @return self - cloned builder
	 */
	public function whereNull($field);

	/**
	 * @param $field
	 * @return self - cloned builder
	 */
	public function whereNotNull($field);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return self - cloned builder
	 */
	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @param SelectQueryBuilderInterface $selectQueryBuilder
	 * @return self - cloned builder
	 */
	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder);

	/**
	 * @return WhereBuilderInterface - new builder
	 */
	public function whereGroup();
}