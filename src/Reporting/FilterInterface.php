<?php

namespace App\Reporting;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\Resources\Table;

interface FilterInterface
{
	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function filterQuery(SelectQueryBuilderInterface $queryBuilder);

	/**
	 * @param Table $table
	 * @return bool
	 */
	public function requiresTable(Table $table);
}