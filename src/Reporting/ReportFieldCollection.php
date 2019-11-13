<?php


namespace App\Reporting;

use Traversable;

class ReportFieldCollection implements \IteratorAggregate
{
	/** @var ReportFieldInterface[] */
	private $fields;

	/**
	 * ReportFieldCollection constructor.
	 * @param ReportFieldInterface[] $fields
	 */
	public function __construct($fields = [])
	{
		$this->fields = $fields;
	}

	/**
	 * @return \Generator|Traversable
	 */
	public function getIterator()
	{
		foreach ($this->fields as $field) {
			yield $field;
		}
	}

	public function getSelected(ReportRequest $request)
	{
		$selected = new SelectedFieldCollection([]);

		foreach ($this->fields as $field) {
			if ($field->selected($request)) {
				$selected = $selected->withField($field->selectField($request));
			}
		}
		return $selected;
	}

	public function withField(ReportFieldInterface $field)
	{
		$clone = clone $this;
		$clone->fields[] = $field;
		return $clone;
	}

	public function withFields(ReportFieldCollection $fields)
	{
		$clone = clone $this;

		foreach ($fields->fields as $field) {
			$clone->fields[] = $field;
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

		return $fields;
	}
}