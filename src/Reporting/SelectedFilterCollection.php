<?php

namespace App\Reporting;

class SelectedFilterCollection implements \IteratorAggregate
{
	/** @var FilterInterface[] */
	private $filters = [];

	/**
	 * SelectedFilterCollection constructor.
	 * @param FilterInterface[] $filters
	 */
	public function __construct($filters = [])
	{
		foreach ($filters as $filter) {
			$this->addFilter($filter);
		}
	}

	public static function makeFromRequestedReportFilters(RequestedFilterCollection $requestedFilters, ReportFilterCollection $reportFilters)
	{
		$selectedFilters = new self([]);

		foreach ($requestedFilters as $requestedFilter) {
			if ($reportFilters->hasFilter($requestedFilter->reportFieldId())) {
				$reportFilter = $reportFilters->getFilter($requestedFilter->reportFieldId());
				$selectedFilter = new SelectedFilter($reportFilter, $requestedFilter->constraint(), $requestedFilter->inputs());
				$selectedFilters = $selectedFilters->withFilter($selectedFilter);
			}
		}

		return $selectedFilters;
	}

	public function getIterator()
	{
		foreach ($this->filters as $filter) {
			yield $filter;
		}
	}

	public function withFilter(FilterInterface $filter)
	{
		$clone = clone $this;
		$clone->addFilter($filter);
		return $clone;
	}

	private function addFilter(FilterInterface $filter)
	{
		$this->filters[] = $filter;
	}
}