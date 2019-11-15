<?php

namespace App\Reporting\Filters;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\Filters\Constraints\AbstractConstraint;
use App\Reporting\ReportFilterInterface;
use App\Reporting\Request\ReportRequest;
use App\Reporting\Resources\Table;
use App\Reporting\SelectedFilter;

abstract class AbstractFilter implements ReportFilterInterface
{
	public function jsonSerialize()
	{
		return [
			'id' => $this->id(),
			'label' => $this->label(),
			'defaultLabel' => $this->label(),
			'groupName' => $this->groupName(),
			'constraints' => $this->constraints(),
			'options' => $this->options(),
		];
	}

	public function selected(ReportRequest $request)
	{
		foreach ($request->fields() as $selectedFilter) {
			if ($selectedFilter['id'] === $this->id()) {
				return true;
			}
		}

		return false;
	}

	public function selectFilter(ReportRequest $request)
	{
		foreach ($request->filters() as $selectedFilter) {
			if ($selectedFilter['id'] === $this->id()) {
				$constraint = AbstractConstraint::getConstraint($selectedFilter['constraint']['name']);
				$inputs = $constraint->inputArrayFromRequestData($selectedFilter['constraint']);

				return new SelectedFilter($this, $constraint, $inputs);
			}
		}

		throw new \LogicException('filter was not selected by request');
	}

	public function requiresTable(Table $table)
	{
		return $this->tableAlias() === $table->alias();
	}

	public function filterQuery(SelectQueryBuilderInterface $queryBuilder, Constrains $constraint, $inputs)
	{
		return $constraint->filterSql($queryBuilder, $this->fieldName(), $inputs);
	}

	abstract public function id();

	abstract public function groupName();

	/**
	 * @return Constrains[]
	 */
	abstract protected function constraints();

	/**
	 * @return string
	 */
	abstract protected function name();

	/**
	 * @return string
	 */
	abstract protected function label();

	/**
	 * @return string
	 */
	abstract protected function fieldName();

	/**
	 * @return string
	 */
	abstract protected function tableAlias();

	/**
	 * @return string
	 */
	abstract protected function tableAsCategory();

	/**
	 * @return string
	 */
	abstract protected function options();
}