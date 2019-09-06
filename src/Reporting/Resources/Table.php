<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 12/28/2018
 * Time: 1:19 PM
 */

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

	/**
	 * Table constructor.
	 * @param string $table_name
	 * @param string $alias
	 * @param DatabaseField[] $fields
	 */
	public function __construct($table_name, $alias)
	{
		$this->table_name = $table_name;
		$this->alias = $alias;
		$this->path = new TableList([$this]);
	}

	public function __debugInfo() {
		return [
//			'table_name' => $this->table_name,
			'alias' => $this->alias,
//			'path' => $this->getPath(),
			'descendant' => $this->descendant(),
		];
	}

	public function name()
	{
		return $this->table_name;
	}

	public function aggregateName()
	{
//		die(var_dump($this->path));
		return $this->alias().'_aggregate';
	}

	public function alias()
	{
		return $this->alias;
	}

	public function primary_key()
	{
		return $this->primary_key;
	}

	/**
	 * @return DatabaseField[]
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @return ReportField[]
	 */
	public function getReportFields()
	{
		$report_fields = [];
		foreach ($this->fields as $databaseField) {
			if ($databaseField->useAsField()) {
				$report_fields[] = new ReportField($databaseField);
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

	/**
	 * @return bool
	 */
	public function descendant()
	{
		return $this->is_descendant;
	}

	public function addDbField(DatabaseField $field)
	{
		$this->fields[$field->name()] = $field;
//		$field->setTable($this);
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
//		var_dump($connecting_table_path);
		$path = new TableList($connecting_table_path->getTables());
		$path->addTable($this);
		$this->setPath($path);

		return $this->relationshipType($table->alias()) === 'child';
	}

	/**
	 * @return TableList
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param TableList $path
	 */
	protected function setPath(TableList $path)
	{
//		var_dump($path);
		$this->path = $path;
	}

	/**
	 * @param QueryGroup $query_group
	 */
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

//	public function requiredTables()
//	{
//		$this->required_tables;
//	}

	/**
	 * @param string $field_name
	 * @return string
	 */
	public function setPrimaryKey($field_name)
	{
		$primary_key = new PrimaryKey($this, $field_name);
		if (is_null($this->primary_key) === false) {

		}
		$this->addDbField($primary_key);
		$this->primary_key = $primary_key;
	}

	/**
	 * @param string $field_name
	 * @return string
	 */
	public function addStringField($field_name)
	{
		$field = new StringField($this, $field_name);
		$this->addDbField($field);
	}

	/**
	 * @param string $field_name
	 * @return string
	 */
	public function addNumberField($field_name)
	{
		$field = new NumberField($this, $field_name);
		$this->addDbField($field);
	}

	/**
	 * @param string $field_name
	 * @return string
	 */
	public function addDateField($field_name)
	{
		$field = new DateField($this, $field_name);
		$this->addDbField($field);
	}

	/**
	 * @param string $field_name
	 * @return string
	 */
	public function addDateTimeField($field_name)
	{
		$field = new DateTimeField($this, $field_name);
		$this->addDbField($field);
	}

	/**
	 * @param string $field_name
	 * @return string
	 */
	public function addBooleanField($field_name)
	{
		$field = new BooleanField($this, $field_name);
		$this->addDbField($field);
	}

	/**
	 * @param string $field_name
	 * @return string
	 */
	public function addForeignKey($field_name)
	{
		$field = new ForeignKey($this, $field_name);
		$this->addDbField($field);
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