<?php

namespace App\Reporting\Request;

class RequestedFields
{
	/** @var RequestedField[] */
	private $fields = [];

	/**
	 * ReportFieldCollection constructor.
	 * @param RequestedField[] $fields
	 */
	public function __construct($fields = [])
	{
		foreach ($fields as $field) {
			$this->addField($field);
		}
	}

	public static function fromRequestDataArray($requestedFieldsData)
	{
		$fields = [];
		foreach ($requestedFieldsData as $requestedFieldData) {
			$fields[] = RequestedField::fromRequestDataArray($requestedFieldData);
		}

		return new self($fields);
	}

	public function getIterator()
	{
		foreach ($this->fields as $field) {
			yield $field;
		}
	}

	public function withField(RequestedField $field)
	{
		$clone = clone $this;
		$clone->addField($field);
		return $clone;
	}

	public function withFields(RequestedFields $fields)
	{
		$clone = clone $this;

		foreach ($fields as $field) {
			$clone->addField($field);
		}

		return $clone;
	}

	private function addField(RequestedField $field)
	{
		$this->fields[] = $field;
	}
}