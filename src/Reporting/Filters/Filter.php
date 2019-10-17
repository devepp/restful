<?php

namespace App\Reporting\Filters;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;
use JsonSerializable;

class Filter implements JsonSerializable
{
	/** @var Table */
	protected $table;
	/** @var DatabaseField */
	protected $dbField;

	/** @var string */
	protected $label;

	public function __construct(Table $table, DatabaseField $dbField, $label = null)
	{
		$this->table = $table;
		$this->dbField = $dbField;

		$this->label = $label ? $label : ucwords(str_replace('_', ' ', $this->dbField->name()));
	}

	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'field_name' => $this->dbField->name(),
			'table_alias' => $this->table->alias(),
			'table' => $this->tableAsCategory(),
			'label' => $this->label(),
			'constraints' => $this->constraints(),
			'url' => $this->url(),
		];
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->dbField->alias($this->table->alias());
	}

	/**
	 * @return string
	 */
	public function label()
	{
		return $this->label;
	}


	public function dbField()
	{
		return $this->dbField;
	}

	public function tableAsCategory()
	{
		return ucwords(str_replace('_', ' ', $this->table->alias()));
	}

	/**
	 * @return Constrains[]
	 */
	public function constraints()
	{
		return $this->dbField->filterConstraints();
	}


	public function url()
	{
		return $this->table->alias();
	}


}