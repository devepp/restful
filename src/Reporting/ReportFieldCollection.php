<?php

namespace App\Reporting;

use App\Reporting\Request\ReportRequest;
use App\Reporting\Request\RequestedField;
use IteratorAggregate;
use JsonSerializable;

class ReportFieldCollection implements IteratorAggregate, JsonSerializable
{
	/** @var ReportFieldInterface[] */
	private $fields = [];

	/**
	 * ReportFieldCollection constructor.
	 * @param ReportFieldInterface[] $fields
	 */
	public function __construct($fields = [])
	{
		foreach ($fields as $field) {
			$this->addField($field);
		}
	}

	public function getIterator()
	{
		if (is_array($this->fields)) {
			return new \ArrayIterator($this->fields);
		}

		return $this->fields;
	}

	public function getSelected(ReportRequest $request)
	{
		$selected = new SelectedFieldCollection([]);

		/** @var RequestedField $requestedField */
		foreach ($request->fields() as $requestedField) {
			if ($this->hasField($requestedField->reportFieldId())) {
				$selectedField = SelectedField::fromRequestField($requestedField, $this->getField($requestedField->reportFieldId()));
				$selected = $selected->withField($selectedField);
			}
		}

		return $selected;
	}

	public function hasField($id)
	{
		return isset($this->fields[$id]);
	}

	public function getField($id)
	{
		return $this->fields[$id];
	}

	public function withField(ReportFieldInterface $field)
	{
		$clone = clone $this;
		$clone->addField($field);
		return $clone;
	}

	public function withFields(ReportFieldCollection $fields)
	{
		$clone = clone $this;

		foreach ($fields->fields as $field) {
			$clone->addField($field);
		}

		return $clone;
	}

	public function asGroupedJsonArray()
	{
		$fields = [];

		foreach ($this->fields as $field) {
			if (!isset($fields[$field->groupName()])) {
				$fields[$field->groupName()]['name'] = $field->groupName();
				$fields[$field->groupName()]['fields'] = [];
			}

			$fields[$field->groupName()]['fields'][] = $field;
		}

		return array_values($fields);
	}

	public function jsonSerialize()
	{
		return $this->fields;
	}

	private function addField(ReportFieldInterface $field)
	{
		$this->fields[$field->id()] = $field;
	}
}