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

class Table
{
	/** @var string */
	protected $table_name;

	/** @var string */
	protected $alias;

	/** @var bool */
	protected $is_descendant = false;

	/** @var PrimaryKey */
	protected $primary_key;

	/** @var DatabaseField[] */
	protected $fields;

	/** @var Relationship[] */
	protected $parent_of_relationships = [];

	/** @var Relationship[] */
	protected $child_of_relationships = [];

	/** @var TableList */
	protected $path;

	public function __construct($table_name, $alias, $fields = [])
	{
		$this->table_name = $table_name;
		$this->alias = $alias;
		$this->fields = $fields;
	}

	public static function builder($tableName)
	{
		return new TableBuilder($tableName);
	}

	public function __debugInfo() {
		return [
			'table_name' => $this->table_name,
			'alias' => $this->alias,
			'descendant' => $this->descendant(),
		];
	}

	public function name()
	{
		return $this->table_name;
	}

	public function aggregateName()
	{
		return $this->alias().'_aggregate';
	}

	public function alias()
	{
		return $this->alias;
	}

	public function primary_key()
	{
		foreach ($this->fields as $field) {
			if ($field instanceof PrimaryKey) {
				return $field;
			}
		}
	}

	public function getFields()
	{
		return $this->fields;
	}

	public function getReportFields()
	{
		$report_fields = [];
		foreach ($this->fields as $databaseField) {
			if ($databaseField->useAsField()) {
				$report_fields[] = new ReportField($this, $databaseField);
			}
		}

		return $report_fields;
	}

	public function setIsDescendant($is_descendant)
	{
		$this->is_descendant = $is_descendant;
	}

	public function setAlias($alias)
	{
		$this->alias = $alias;
	}

	public function descendant()
	{
		return $this->is_descendant;
	}

	public function dbField($field_name)
	{
		if (isset($this->fields[$field_name])) {
			return $this->fields[$field_name];
		}

		throw new \LogicException($field_name.' does not exist on table '.$this->name());
	}

	public function isParentOf(Table $table, $relationship)
	{
		$this->parent_of_relationships[$table->alias()] = $relationship;
	}

	public function isChildOf(Table $table, $relationship)
	{
		$this->child_of_relationships[$table->alias()] = $relationship;
	}

	public function connectTable(Table $table)
	{
		$connecting_table_path = $table->getPath();
		$path = new TableList($connecting_table_path->getTables());
		$path->addTable($this);
		$this->setPath($path);

		return $this->relationshipType($table->alias()) === 'child';
	}

	public function getPath()
	{
		return $this->path;
	}

	protected function setPath(TableList $path)
	{
		$this->path = $path;
	}

	public function getRelationshipAliases()
	{
		return array_merge(array_keys($this->parent_of_relationships), array_keys($this->child_of_relationships));
	}

	public function relationshipType($table_alias)
	{
		if (isset($this->child_of_relationships[$table_alias])) {
			return 'child';
		} elseif (isset($this->parent_of_relationships[$table_alias])) {
			return 'parent';
		}
		return false;
	}

	public function joinSql($current_table_aliases)
	{
		$relationship = $this->findRelationship($current_table_aliases);
		return 'LEFT JOIN `'.$this->name().'` `'.$this->alias().'` ON '.$relationship->joinConditionsSql();
	}

	protected function findRelationship($current_table_aliases)
	{
		foreach ($current_table_aliases as $table_alias) {
			if (isset($this->child_of_relationships[$table_alias])) {
				return $this->child_of_relationships[$table_alias];
			} elseif (isset($this->parent_of_relationships[$table_alias])) {
				return $this->parent_of_relationships[$table_alias];
			}
		}
	}

}