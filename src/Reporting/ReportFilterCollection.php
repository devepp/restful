<?php

namespace App\Reporting;

use Traversable;

class ReportFilterCollection implements \IteratorAggregate
{
	/** @var ReportFilterInterface[] */
	private $filters = [];

	/**
	 * ReportFilterCollection constructor.
	 * @param ReportFilterInterface[] $filters
	 */
	public function __construct($filters = [])
	{
		foreach ($filters as $filter) {
			$this->addFilter($filter);
		}
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

	public function hasFilter($id)
	{
		return isset($this->filters[$id]);
	}

	public function getFilter($id)
	{
		return $this->filters[$id];
	}

//	public function getSelected(ReportRequest $request)
//	{
//		$selected = new SelectedFilterCollection([]);
//
//		foreach ($this->filters as $filter) {
//			if ($filter->selected($request)) {
//				$selected = $selected->withFilter($filter->selectFilter($request));
//			}
//		}
//		return $selected;
//	}

	public function withFilter(ReportFilterInterface $filter)
	{
		$clone = clone $this;

		$clone->addFilter($filter);

		return $clone;
	}

	public function withFilters(ReportFilterCollection $filters)
	{
		$clone = clone $this;

		foreach ($filters as $filter) {
			$clone->addFilter($filter);
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

		return array_values($filters);
	}

	private function addFilter(ReportFilterInterface $filter)
	{
		$this->filters[$filter->id()] = $filter;
	}
}