<?php

namespace App\Reporting;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\Resources\Table;
use App\Reporting\Selectables\AbstractSelectable;
use JsonSerializable;

class SelectedField implements FieldInterface, JsonSerializable
{
	/** @var ReportFieldInterface */
	protected $field;

	/** @var AbstractSelectable */
	protected $selectable;

	/** @var string */
	protected $label;

	/**
	 * SelectedField constructor.
	 * @param ReportFieldInterface $field
	 * @param AbstractSelectable $selectable
	 * @param string $label
	 */
	public function __construct(ReportFieldInterface $field, AbstractSelectable $selectable,  $label)
	{
		$this->field = $field;
		$this->selectable = $selectable;
		$this->label = $label;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		$jsonData = $this->field->jsonSerialize();

//		$jsonData['title'] = $jsonData['label'];
		$jsonData['modifier'] = $this->selectable->name();
		$jsonData['label'] = $this->label;

		return $jsonData;
	}

	public function __debugInfo()
	{
		return [
//			'field' => $this->field,
//			'selectable' => $this->selectable->name(),
			'label' => $this->label,
			'table' => $this->table(),
		];
	}


	/**
	 * @return string
	 */
	public function name()
	{
		return $this->field->name();
	}

	/**
	 * @return string
	 */
	public function label()
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function title()
	{
		return $this->field->title();
	}

	public function formatValueAsType($value)
	{
		return $this->field->formatValueAsType($value);
	}

	/**
	 * @return string
	 */
	public function table()
	{
		return $this->field->tableAlias();
	}

	public function addToQuery(SelectQueryBuilderInterface $queryBuilder)
	{
		$selectField = $this->selectable->selectField('`'.$this->field->tableAlias().'`.`'.$this->field->fieldName().'`');
		$alias = $this->selectable->alias($this->field->tableAlias().'__'.$this->field->fieldName());

		return $queryBuilder->select($selectField.' '.$alias);
	}

	public function addToQueryAsAggregate(SelectQueryBuilderInterface $queryBuilder, $aggregateAlias)
	{
		$selectField = $aggregateAlias.'.'.$this->selectable->alias($this->field->tableAlias().'__'.$this->field->fieldName());

		return $queryBuilder->select($selectField);
	}

	public function requiresTable(Table $table)
	{
		return $this->field->requiresTable($table);
	}

	/**
	 * @param QueryGroup $subQueryGroup
	 * @return string
	 */
	public function fieldAlias($subQueryGroup)
	{
		return $this->selectable->fieldAlias($this->field, $subQueryGroup);
	}


}