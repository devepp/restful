<?php

namespace App\Reporting\Resources;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DatabaseFields\PrimaryKey;
use App\Reporting\DB\QueryBuilder\QueryBuilderInterface;
use App\Reporting\Filters\Filter;
use App\Reporting\ReportField;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;
use App\Reporting\Resources\TableCollectionFunctions\Filters\DirectlyRelatedTo;
use App\Reporting\Selectables\Standard;

class Table
{
	/** @var TableName */
	protected $name;

	/** @var DatabaseField[] */
	protected $fields = [];

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

	public function __debugInfo()
	{
		return [
			'name' => $this->name(),
			'alias' => $this->alias(),
			'fields' => \array_map(function (DatabaseField $field){
				return $field->name();
			}, $this->fields),
			'relationships' => \array_map(function (RelationshipInterface $relationship){
				return $relationship->__debugInfo();
			}, $this->relationships),

		];
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

	public function hasField($fieldName)
	{
		return isset($this->fields[$fieldName]);
	}

	public function dbField($fieldName)
	{
		if (isset($this->fields[$fieldName])) {
			return $this->fields[$fieldName];
		}

		throw new \LogicException($fieldName.' does not exist on table '.$this->name());
	}

	/**
	 * @return ReportFieldInterface[]
	 */
	public function getReportFields($sameNode = true)
	{
		$reportFields = [];
		foreach ($this->fields as $databaseField) {
			if ($databaseField->useAsField()) {
				$selectables = $sameNode ? [new Standard()] : $databaseField->selectables();
				$reportFields[] = new ReportField($this, $databaseField, $selectables);
			}
		}

		return $reportFields;
	}

	/**
	 * @return ReportFilterInterface[]
	 */
	public function getReportFilters($sameNode = true)
	{
		$reportFilters = [];
		foreach ($this->fields as $databaseField) {
			if ($databaseField->useAsFilter()) {
				$reportFilters[] = new Filter($this, $databaseField);
			}
		}

		return $reportFilters;
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

	public function joinCondition(Table $table)
	{
		if ($this->relatedTo($table->alias())) {
			$relationship = $this->relationships[$table->alias()];

			return $relationship->condition();
		}
		if ($table->relatedTo($this->alias())) {
			return $table->joinCondition($this);
		}

//		\var_dump($this->alias());
//		\var_dump($this->relationships);
//		\var_dump($table->alias());
		throw new \LogicException('trying to get relation condition on `'.$this->alias().'` from unrelated table. `'.$table->alias().'`');
	}

	/**
	 * @param Table $pathTo
	 * @param TableCollection $availableTables
	 * @param TableCollection|null $pathSoFar
	 * @return TableCollection|null
	 */
	public function pathTo(Table $pathTo, TableCollection $availableTables, TableCollection $pathSoFar = null)
	{
		if ($pathSoFar === null) {
			$pathSoFar = new TableCollection();
		}

		if (!$pathSoFar->hasTable($this)) {
			$pathSoFar = $pathSoFar->addTable($this);
		}

		if ($this->alias() === $pathTo->alias()) {
			return $pathSoFar;
		}

		$relatedTables = $availableTables->filter(new DirectlyRelatedTo($this));
		$possiblePaths = [];

		foreach ($relatedTables as $relatedTable) {
			if (!$pathSoFar->hasTable($relatedTable)) {
				$newPathSoFar = $pathSoFar->addTable($relatedTable);
				$path = $relatedTable->pathTo($pathTo, $availableTables, $newPathSoFar);
				if ($path) {
					$possiblePaths[] = $path;
				}
			}
		}

		$shortestPath = null;
		/** @var TableCollection $possiblePath */
		foreach ($possiblePaths as $possiblePath) {
			if ($shortestPath === null) {
				$shortestPath = $possiblePath;
			} else {
				/** @var TableCollection $shortestPath */
				$shortestPath = ($shortestPath->count() < $possiblePath->count()) ? $shortestPath : $possiblePath;
			}
		}

		return $shortestPath;
	}

	public function sameNodeAs(Table $table, TableCollection $tableCollection)
	{
		$path = $this->pathTo($table, $tableCollection);

		if ($path) {
			for ($i = 0; $i < $path->count() - 1; $i++) {
				$path->seek($i);
				$firstTable = $path->current();
				$path->seek($i + 1);
				$secondTable = $path->current();

				if($firstTable->relatedTo($secondTable->alias()) && $firstTable->hasOne($secondTable->alias()) === false) {
					return false;
				}

				if ($secondTable->relatedTo($firstTable->alias()) && $secondTable->hasOne($firstTable->alias())) {
					return false;
				}
			}

			return true;
		}

		return false;
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