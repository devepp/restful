<?php

namespace App\Reporting\DB;

use App\Reporting\DB\QueryBuilder\DeleteQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\InsertQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilder\UpdateQueryBuilderInterface;
use PDO;

interface ConnectionInterface
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
}