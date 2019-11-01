<?php

namespace App\Reporting\DB\Schema;

class Table
{
	/** @var string */
	protected $name;

	/** @var FieldInterface[] */
	protected $fields = [];

	/**
	 * Table constructor.
	 * @param string $name
	 * @param FieldInterface[] $fields
	 */
	public function __construct(string $name, $fields = [])
	{
		$this->name = $name;

		foreach ($fields as $field) {
			$this->indexField($field);
		}
	}

	public static function builder($tableName)
	{
		return new TableBuilder($tableName);
	}

	public function __toString()
	{
		return $this->name;
	}

	public function name()
	{
		return $this->name;
	}

	public function fields()
	{
		return array_values($this->fields);
	}

	public function hasField(string $fieldName)
	{
		return isset($this->fields[$fieldName]);
	}

	public function field(string $fieldName)
	{
		if (isset($this->fields[$fieldName])) {
			return $this->fields[$fieldName];
		}

		throw new \LogicException($fieldName.' does not exist on table '.$this->name());
	}

	private function indexField(FieldInterface $field)
	{
		$this->fields[$field->__toString()] = $field;
	}
}