<?php

namespace App\Reporting\DB;

use App\Reporting\DB\QueryBuilder\DeleteQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\InsertQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\UpdateQueryBuilderInterface;
use PDO;

interface DbConnectionInterface
{
	/**
	 * @return PDO
	 */
	public function getPDO();

	/**
	 * @param Query $query
	 * @return Result
	 */
	public function execute(Query $query);

	public function beginTransaction();

	public function commit();

	public function rollback();

	public function lastInsertId();

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