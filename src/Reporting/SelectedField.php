<?php

namespace App\Reporting;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\Resources\Table;
use App\Reporting\Selectables\AbstractSelectable;
use JsonSerializable;

class SelectedField implements JsonSerializable
{
	/** @var DatabaseField */
	protected $field;

	/** @var Table */
	protected $table;

	/** @var AbstractSelectable */
	protected $selectable;

	/**
	 * SelectedField constructor.
	 * @param Table $table
	 * @param DatabaseField $dbField
	 * @param AbstractSelectable $selectable
	 */
	public function __construct(Table $table, DatabaseField $dbField, AbstractSelectable $selectable)
	{
		$this->table = $table;
		$this->field = $dbField;
		$this->selectable = $selectable;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'alias' => $this->fieldAlias(false),
			'title' => $this->title(),
			'label' => $this->label(),
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
		return $this->field->alias();
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


	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @param $subQueryGroup
	 * @return SelectQueryBuilderInterface
	 */
	public function fieldSql(SelectQueryBuilderInterface $queryBuilder, $subQueryGroup)
	{
		return $queryBuilder->select($this->selectable->fieldSql($this->table, $this->field, $subQueryGroup));
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