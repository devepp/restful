<?php

namespace App\Reporting\Filters;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use JsonSerializable;

interface Constrains extends JsonSerializable
{
	/**
	 * @return string
	 */
	public function name();

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @param string $field
	 * @param array $inputs
	 * @return SelectQueryBuilderInterface
	 */
	public function filterSql(SelectQueryBuilderInterface $queryBuilder,  $field, $inputs = []);

	/**
	 * @return string
	 */
	public function directive();	// return string - name of directive to use

	/**
	 * @return int
	 */
	public function requiredInputs();	//return int - number of inputs required by constraint

	/**
	 * @param array $constraintData
	 * @return array
	 */
	public function inputArrayFromRequestData($constraintData);
}