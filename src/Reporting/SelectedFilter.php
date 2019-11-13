<?php

namespace App\Reporting;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\Filters\Constraints\AbstractConstraint;
use App\Reporting\Resources\Table;
use JsonSerializable;

class SelectedFilter implements FilterInterface, JsonSerializable
{
	/** @var ReportFilterInterface */
	protected $filter;

	/** @var string */
	protected $label;

	/** @var AbstractConstraint */
	protected $constraint;

	/** @var array */
	protected $inputs;

	/**
	 * SelectedFilter constructor.
	 * @param ReportFilterInterface $filter
	 * @param AbstractConstraint $constraint
	 * @param array $inputs
	 */
	public function __construct(ReportFilterInterface $filter, AbstractConstraint $constraint, $inputs = [])
	{
		$this->filter = $filter;
		$this->constraint = $constraint;
		$this->inputs = $inputs;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->filter->jsonSerialize();
	}

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function filterQuery(SelectQueryBuilderInterface $queryBuilder)
	{
		return $this->filter->filterQuery($queryBuilder, $this->constraint, $this->inputs);
	}

	public function requiresTable(Table $table)
	{
		return $this->filter->requiresTable($table);
	}
}