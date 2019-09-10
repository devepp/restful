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
	protected $db_field;

	/** @var string */
	protected $label;

	public function __construct(Table $table, DatabaseField $db_field, $label = null)
	{
		$this->table = $table;
		$this->db_field = $db_field;

		$this->label = $label ? $label : ucwords(str_replace('_', ' ', $this->db_field->name()));
	}

	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'field_name' => $this->db_field->name(),
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
		return $this->db_field->alias($this->table->alias());
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
		return $this->db_field;
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
		return $this->db_field->filterConstraints();
	}


	public function url()
	{
		return $this->table->alias();
	}


}