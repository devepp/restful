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
use App\Reporting\Resources\Relationships\ManyToOne;
use App\Reporting\Resources\Relationships\OneToOne;

class TableBuilder
{
	/** @var string */
	protected $name;

	/** @var DatabaseField[] */
	protected $fields = [];

	/** @var RelationshipInterface[] */
	protected $relationships = [];

	public function __construct($tableName)
	{
		$this->name = new TableName($tableName);
	}

	public function build()
	{
		return new Table($this->name, $this->fields, $this->relationships);
	}

	public function setAlias($alias)
	{
		$clone = clone $this;
		$clone->name = new TableName($this->name, $alias);
		return $clone;
	}

	public function setPrimaryKey($fieldName)
	{
		$primaryKey = new PrimaryKey($fieldName);

		$clone = clone $this;
		$clone->addDbField($primaryKey);
		$clone->primaryKey = $primaryKey;
		return $clone;
	}

	public function addStringField($fieldName)
	{
		$clone = clone $this;
		$field = new StringField($fieldName);
		$clone->addDbField($field);
		return $clone;
	}

	public function addNumberField($fieldName)
	{
		$clone = clone $this;
		$field = new NumberField($fieldName);
		$clone->addDbField($field);
		return $clone;
	}

	public function addDateField($fieldName)
	{
		$clone = clone $this;
		$field = new DateField($fieldName);
		$clone->addDbField($field);
		return $clone;
	}

	public function addDateTimeField($fieldName)
	{
		$clone = clone $this;
		$field = new DateTimeField($fieldName);
		$clone->addDbField($field);
		return $clone;
	}

	public function addBooleanField($fieldName)
	{
		$clone = clone $this;
		$field = new BooleanField($fieldName);
		$clone->addDbField($field);
		return $clone;
	}

	public function addManyToOneRelationship(TableName $tableName, $condition, $foreignKey)
	{
		$clone = clone $this;
		$field = new ForeignKey($foreignKey, $tableName);
		$clone->addDbField($field);
		$clone->relationships[] = new ManyToOne($this->name, $tableName, $condition);
		return $clone;
	}

	public function addOneToManyRelationship(TableName $tableName, $condition)
	{
		$clone = clone $this;
		$clone->relationships[] = new ManyToOne($tableName, $this->name, $condition);
		return $clone;
	}

	public function addOneToOneRelationship(TableName $tableName, $condition, $foreignKey = null)
	{
		$clone = clone $this;
		if ($foreignKey) {
			$field = new ForeignKey($foreignKey, $tableName);
			$clone->addDbField($field);
		}
		$clone->relationships[] = new OneToOne($this->name, $tableName, $condition);
		return $clone;
	}

	private function addDbField(DatabaseField $field)
	{
		$this->fields[] = $field;
	}
}