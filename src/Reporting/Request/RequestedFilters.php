<?php

namespace App\Reporting\Request;

use IteratorAggregate;

class RequestedFilters implements IteratorAggregate
{
	/** @var RequestedFilter[] */
	private $filters = [];

	/**
	 * ReportFilterCollection constructor.
	 * @param RequestedFilter[] $filters
	 */
	public function __construct($filters = [])
	{
		foreach ($filters as $filter) {
			$this->addFilter($filter);
		}
	}

	public static function fromRequestDataArray($requestedFiltersData)
	{
		$filters = [];
		foreach ($requestedFiltersData as $requestedFilterData) {
			$filters[] = RequestedFilter::fromRequestDataArray($requestedFilterData);
		}

		return new self($filters);
	}

	public function getIterator()
	{
		foreach ($this->filters as $filter) {
			yield $filter;
		}
	}

	public function withFilter(RequestedFilter $filter)
	{
		$clone = clone $this;
		$clone->addFilter($filter);
		return $clone;
	}

	public function withFilters(RequestedFilters $filters)
	{
		$clone = clone $this;

		foreach ($filters as $filter) {
			$clone->addFilter($filter);
		}

		return $clone;
	}

	private function addFilter(RequestedFilter $filter)
	{
		$this->filters[] = $filter;
	}
}