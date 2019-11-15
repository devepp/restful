<?php

namespace App\Reporting\Request;

class Groupings implements \IteratorAggregate
{
	private $groupings= [];

	/**
	 * RequestedGroupingCollection constructor.
	 * @param $groupings
	 */
	public function __construct($groupings)
	{
		foreach ($groupings as $grouping) {
			$this->addGrouping($grouping);
		}
	}

	public static function fromRequestDataArray($requestGroupingsData)
	{
		$groupings = [];
		foreach ($requestGroupingsData as $groupingData) {
			$groupings[] = new Grouping($groupingData['id']);
		}
		return new self($groupings);
	}

	public function getIterator()
	{
		foreach ($this->groupings as $grouping) {
			yield $grouping;
		}
	}

	public function withGrouping(Grouping $grouping)
	{
		$clone = clone $this;
		$clone->addGrouping($grouping);
		return $clone;
	}

	private function addGrouping(Grouping $grouping)
	{
		$this->groupings[] = $grouping;
	}
}