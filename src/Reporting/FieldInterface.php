<?php

namespace App\Reporting;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\Resources\Table;

interface FieldInterface
{
	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function addToQuery(SelectQueryBuilderInterface $queryBuilder);
	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function addToQueryAsAggregate(SelectQueryBuilderInterface $queryBuilder, $aggregateAlias);

	/**
	 * @param Table $table
	 * @return bool
	 */
	public function requiresTable(Table $table);


//	public function addToSubQuery(SelectQueryBuilderInterface $queryBuilder);
//
//
//	public function addToOuterQuery(SelectQueryBuilderInterface $queryBuilder);
}