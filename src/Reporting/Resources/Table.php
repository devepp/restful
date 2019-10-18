<?php

namespace App\Reporting\Resources;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DatabaseFields\PrimaryKey;
use App\Reporting\DB\QueryBuilder\QueryBuilderInterface;
use App\Reporting\ReportField;

class Table
{
	/** @var TableName */
	protected $name;

	/** @var DatabaseField[] */
	protected $fields;

	/** @var RelationshipInterface[] */
	protected $relationships = [];

	/**
	 * Table constructor.
	 * @param TableName $name
	 * @param DatabaseField[] $fields
	 * @param RelationshipInterface[] $relationships
	 */
	public function __construct(TableName $name, $fields = [], $relationships = [])
	{
		$this->name = $name;

		foreach ($fields as $field) {
			$this->indexField($field);
		}

		foreach ($relationships as $relationship) {
			$this->indexRelationship($relationship);
		}
	}

	public static function fromString($tableName, $alias = null, $fields = [], $relationships = [])
	{
		return new self(new TableName($tableName, $alias), $fields, $relationships);
	}

	public static function builder($tableName)
	{
		return new TableBuilder($tableName);
	}

	public function __toString()
	{
		return $this->name->__toString();
	}

	public function tableName()
	{
		return $this->name;
	}

	public function name()
	{
		return $this->name->name();
	}

	public function aggregateName()
	{
		return $this->name->aggregateName();
	}

	public function alias()
	{
		return $this->name->alias();
	}

	public function primaryKey()
	{
		foreach ($this->fields as $field) {
			if ($field instanceof PrimaryKey) {
				return $field;
			}
		}

		return null;
	}

	public function getFields()
	{
		return array_values($this->fields);
	}

	public function hasField(string $fieldName)
	{
		return isset($this->fields[$fieldName]);
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

	public function dbField($fieldName)
	{
		if (isset($this->fields[$fieldName])) {
			return $this->fields[$fieldName];
		}

		throw new \LogicException($fieldName.' does not exist on table '.$this->name());
	}

	/**
	 * @param $tableAlias
	 * @return bool
	 */
	public function relatedTo($tableAlias)
	{
		return isset($this->relationships[$tableAlias]);
	}

	/**
	 * @param $tableAlias
	 * @return bool
	 */
	public function hasOne($tableAlias)
	{
		if ($this->relatedTo($tableAlias) === false) {
			$thisTableName = $this->name->name();
			throw new \LogicException("tableAlias `$tableAlias` not related to table `$thisTableName`");
		}

		$relationship = $this->relationships[$tableAlias];

		return $relationship->tableHasOne($this->alias(), $tableAlias);
	}

	public function joinSql(QueryBuilderInterface $queryBuilder, $current_table_aliases)
	{
		//TODO fix this or remove it
//		$relationship = $this->findRelationship($current_table_aliases);
//		return 'LEFT JOIN `'.$this->name().'` `'.$this->alias().'` ON '.$relationship->joinConditionsSql();
	}

	private function indexField(DatabaseField $field)
	{
		$this->fields[$field->name()] = $field;
	}

	private function indexRelationship(RelationshipInterface $relationship)
	{

		$this->relationships[$relationship->getOtherAlias($this->alias())] = $relationship;
	}

}