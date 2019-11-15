<?php

namespace App\Reporting\Request;

use App\Reporting\Common\Values;

class Sorts implements \IteratorAggregate
{
	private $sorts;

	/**
	 * RequestedSortCollection constructor.
	 * @param $sorts
	 */
	public function __construct($sorts = [])
	{
		foreach ($sorts as $sort) {
			$this->addSort($sort);
		}
	}

	public static function fromRequestDataArray($requestSortsData)
	{
		$sorts = [];
		foreach ($requestSortsData as $sortData) {
			$sort = Values::fromArray($sortData);
			$sorts[] = new Sort($sort->valueOrFail('id'), $sort->value('direction'));
		}
		return new self($sorts);
	}

	public function getIterator()
	{
		foreach ($this->sorts as $sort) {
			yield $sort;
		}
	}

	public function withSort(Sort $sort)
	{
		$clone = clone $this;
		$clone->sorts[] = $sort;
		return $clone;
	}

	private function addSort(Sort $sort)
	{
		$this->sorts[] = $sort;
	}
}