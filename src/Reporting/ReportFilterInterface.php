<?php

namespace App\Reporting;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\Filters\Constrains;
use App\Reporting\Request\ReportRequest;
use App\Reporting\Resources\Table;
use JsonSerializable;

interface ReportFilterInterface extends JsonSerializable
{

	public function id();

	public function groupName();

	/**
	 * @param ReportRequest $request
	 * @return bool
	 */
	public function selected(ReportRequest $request);

	/**
	 * @param ReportRequest $request
	 * @return FilterInterface
	 */
	public function selectFilter(ReportRequest $request);

	public function requiresTable(Table $table);

	public function filterQuery(SelectQueryBuilderInterface $queryBuilder, Constrains $constraint, $inputs);
}