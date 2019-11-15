<?php

namespace App\Reporting\DB\QueryBuilder\BuilderInterfaceParts;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

interface JoinsInterface
{
	/**
	 * @param $table
	 * @param $on
	 * @param string $type
	 * @return self
	 */
	public function join($table, $on, $type = 'inner');

	/**
	 * @param SelectQueryBuilderInterface $subQuery
	 * @param $alias
	 * @param $on
	 * @param string $type
	 * @return self
	 */
	public function joinSubQuery(SelectQueryBuilderInterface $subQuery, $alias, $on, $type = 'inner');
}