<?php

namespace App\Reporting;

class SelectedFieldCollection implements \IteratorAggregate
{
	/** @var FieldInterface[] */
	private $selectedFields;

	/**
	 * SelectedFieldCollection constructor.
	 * @param $selectedFields
	 */
	public function __construct($selectedFields = [])
	{
		$this->selectedFields = $selectedFields;
	}

	/**
	 * @return \Generator|\Traversable
	 */
	public function getIterator()
	{
		foreach ($this->selectedFields as $selectedField) {
			yield $selectedField;
		}
	}

	public function withField(FieldInterface $field)
	{
		$clone = clone $this;
		$clone->selectedFields[] = $field;
		return $clone;
	}
}