<?php

namespace App\Reporting\DB\QueryBuilder;

interface InsertQueryBuilderInterface extends QueryBuilderInterface
{
	/**
	 * @param $fieldName
	 * @param $value
	 * @return InsertQueryBuilderInterface
	 */
	public function setValue($fieldName, $value);

	/**
	 * @param $values
	 * @return InsertQueryBuilderInterface
	 */
	public function setValues($values);

	/**
	 * @param SelectQueryBuilderInterface $selectQuery
	 * @return InsertQueryBuilderInterface
	 */
	public function insertSubQuery(SelectQueryBuilderInterface $selectQuery);
}