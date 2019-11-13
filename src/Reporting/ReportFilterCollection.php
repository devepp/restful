<?php


namespace App\Reporting;

use Traversable;

class ReportFilterCollection implements \IteratorAggregate
{
	/** @var ReportFilterInterface[] */
	private $filters;

	/**
	 * ReportFilterCollection constructor.
	 * @param ReportFilterInterface[] $filters
	 */
	public function __construct($filters = [])
	{
		$this->filters = $filters;
	}

	/**
	 * @return \Generator|Traversable
	 */
	public function getIterator()
	{
		foreach ($this->filters as $filter) {
			yield $filter;
		}
	}

	public function getSelected(ReportRequest $request)
	{
		$selected = new SelectedFilterCollection([]);

		foreach ($this->filters as $filter) {
			if ($filter->selected($request)) {
				$selected = $selected->withFilter($filter->selectFilter($request));
			}
		}
		return $selected;
	}

	public function withFilter(ReportFilterInterface $filter)
	{
		$clone = clone $this;

		$clone->filters[] = $filter;

		return $clone;
	}

	public function withFilters(ReportFilterCollection $filters)
	{
		$clone = clone $this;

		foreach ($filters->filters as $filter) {
			$clone->filters[] = $filter;
		}

		return $clone;
	}

	public function asGroupedJsonArray()
	{
		$filters = [];

		foreach ($this->filters as $filter) {
			if (!isset($filters[$filter->groupName()])) {
				$filters[$filter->groupName()]['name'] = $filter->groupName();
				$filters[$filter->groupName()]['filters'] = [];
			}

			$filters[$filter->groupName()]['filters'][] = $filter;
		}

		return $filters;
	}
}