<?php

namespace App\Reporting\DB;

use App\Reporting\DB\QueryBuilder\DeleteQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\InsertQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\UpdateQueryBuilderInterface;

interface QueryBuilderFactoryInterface
{
	/**
	 * @param $tableExpression
	 * @return SelectQueryBuilderInterface
	 */
	public function selectFrom($tableExpression);

	/**
	 * @param $tableExpression
	 * @return UpdateQueryBuilderInterface
	 */
	public function update($tableExpression);

	/**
	 * @param $tableExpression
	 * @return InsertQueryBuilderInterface
	 */
	public function insertInto($tableExpression);

	/**
	 * @param $tableExpression
	 * @return DeleteQueryBuilderInterface
	 */
	public function deleteFrom($tableExpression);
}