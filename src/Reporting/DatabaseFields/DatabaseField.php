<?php

namespace App\Reporting\DatabaseFields;

use App\Reporting\ReportField;
use App\Reporting\Resources\Table;
use App\Reporting\Selectables\Standard;

abstract class DatabaseField
{
	/** @var Table */
	protected $table;
	/** @var string */
	protected $name;

	abstract public function selectableOptions();
	abstract public function filterConstraints();
	abstract public function formatParameter($parameter, $prepend = null, $append = null);

	/**
	 * DatabaseField constructor.
	 * @param string $name
	 */
	public function __construct(Table $table, $name)
	{
		$this->table = $table;
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return '`'.$this->tableAlias().'`.`'.$this->name().'`';
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function alias()
	{
		return $this->table->alias().'__'.$this->name();
	}

	/**
	 * @return string
	 */
	public function title()
	{
		return ucwords(str_replace('_', ' ',$this->table->alias().' '.$this->name()));
	}

	/**
	 * @return string
	 */
	public function tableAlias()
	{
		return $this->table->alias();
	}

	/**
	 * @return string
	 */
	public function tableAggregateAlias()
	{
		return $this->table->aggregateName();
	}

	/**
	 * @return array
	 */
	public function selectables()
	{
		if ($this->table->descendant()) {
			return $this->selectableOptions();
		}

		return [new Standard()];
	}

	/**
	 * @return bool
	 */
	public function fromDescendantTable()
	{
		return $this->table->descendant();
	}


	/**
	 * @return string
	 */
	public function subQueryTableAlias()
	{
		return $this->table->alias();
	}


	/**
	 * @return string
	 */
	public function subQueryField()
	{
		return $this->field->tableAlias();
	}


	/**
	 * @return string
	 */
	public function subQueryAlias()
	{
		return $this->field->tableAlias();
	}


	/**
	 * @return string
	 */
	public function primaryQueryTableAlias()
	{
		return $this->field->tableAlias();
	}


	/**
	 * @return string
	 */
	public function primaryQueryField()
	{
		return $this->field->tableAlias();
	}


	/**
	 * @return string
	 */
	public function primaryQueryAlias()
	{
		return $this->field->tableAlias();
	}

	/**
	 * @return bool
	 */
	public function useAsField()
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function useAsFilter()
	{
		return true;
	}

	/**
	 * @return array
	 */
	public function constraints()
	{
		if ($this->table->descendant()) {
			return $this->selectableOptions();
		}

		return [new Standard()];
	}

	public function formatValueAsType($value)
	{
		return $value;
	}

}