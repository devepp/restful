<?php

namespace App\Reporting;

use IteratorAggregate;
use JsonSerializable;

class SelectedFieldCollection implements IteratorAggregate, JsonSerializable
{
	/** @var FieldInterface[] */
	private $selectedFields;

	/**
	 * SelectedFieldCollection constructor.
	 * @param $selectedFields
	 */
	public function __construct($selectedFields = [])
	{
		foreach ($selectedFields as $field) {
			$this->addField($field);
		}
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
		$clone->addField($field);
		return $clone;
	}

	public function jsonSerialize()
	{
		return $this->selectedFields;
	}

	private function addField(FieldInterface $field)
	{
		$this->selectedFields[] = $field;
	}
}