<?php

namespace App\Reporting;

class SelectedFilterCollection implements \IteratorAggregate
{
	/** @var FilterInterface[] */
	private $filters;

	/**
	 * SelectedFilterCollection constructor.
	 * @param FilterInterface[] $filters
	 */
	public function __construct($filters = [])
	{
		$this->filters = array_map(function(FilterInterface $selectedFilter) { return $selectedFilter; }, $filters);
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
		$clone->filters[] = $filter;
		return $clone;
	}
}