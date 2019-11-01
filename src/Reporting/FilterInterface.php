<?php

namespace App\Reporting;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;

interface FilterInterface
{
	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function filterQuery(SelectQueryBuilderInterface $queryBuilder);
}