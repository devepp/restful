<?php

namespace App\Reporting\Resources;

use App\Reporting\DatabaseFields\BooleanField;
use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DatabaseFields\DateField;
use App\Reporting\DatabaseFields\DateTimeField;
use App\Reporting\DatabaseFields\Field;
use App\Reporting\DatabaseFields\ForeignKey;
use App\Reporting\DatabaseFields\NumberField;
use App\Reporting\DatabaseFields\PrimaryKey;
use App\Reporting\DatabaseFields\StringField;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\Processing\QueryPath;
use App\Reporting\ReportField;

class TableBuilder
{
	/** @var string */
	protected $table_name;

	/** @var string */
	protected $alias;

	/** @var PrimaryKey */
	protected $primary_key;

	/** @var DatabaseField[] */
	protected $fields;

	public function __construct($table_name)
	{
		$this->table_name = $table_name;
		$this->alias = $table_name;
	}

	public function alias($alias)
	{
		$clone = clone $this;
		$clone->alias = $alias;
		return $clone;
	}

	public function setPrimaryKey($field_name)
	{
		$primary_key = new PrimaryKey($field_name);

		$clone = clone $this;

		if (is_null($this->primary_key) === false) {

		}
		$clone->addDbField($primary_key);
		$clone->primary_key = $primary_key;
		return $clone;
	}

	public function addStringField($field_name)
	{
		$clone = clone $this;
		$field = new StringField($field_name);
		$clone->addDbField($field);
		return $clone;
	}

	public function addNumberField($field_name)
	{
		$clone = clone $this;
		$field = new NumberField($field_name);
		$clone->addDbField($field);
		return $clone;
	}

	public function addDateField($field_name)
	{
		$clone = clone $this;
		$field = new DateField($field_name);
		$clone->addDbField($field);
		return $clone;
	}

	public function addDateTimeField($field_name)
	{
		$clone = clone $this;
		$field = new DateTimeField($field_name);
		$clone->addDbField($field);
		return $clone;
	}

	public function addBooleanField($field_name)
	{
		$clone = clone $this;
		$field = new BooleanField($field_name);
		$clone->addDbField($field);
		return $clone;
	}

	public function addForeignKey($field_name)
	{
		$clone = clone $this;
		$field = new ForeignKey($field_name);
		$clone->addDbField($field);
		return $clone;
	}

	public function build()
	{
		return new Table($this->table_name, $this->alias, $this->fields);
	}

	private function addDbField(DatabaseField $field)
	{
		$this->fields[$field->name()] = $field;
	}

}