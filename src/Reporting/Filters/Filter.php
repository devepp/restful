<?php

namespace App\Reporting\Filters;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Filter extends AbstractFilter
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

	public function id()
	{
		return $this->dbField->alias();
	}

	public function groupName()
	{
		return ucwords(str_replace('_', ' ', $this->table->alias()));
	}



	protected function name()
	{
		return $this->dbField->alias();
	}

	protected function label()
	{
		return $this->label;
	}

	protected function fieldName()
	{
		return $this->dbField->name();
	}

	protected function tableAlias()
	{
		return $this->dbField;
	}

	protected function tableAsCategory()
	{
		return ucwords(str_replace('_', ' ', $this->table->alias()));
	}

	/**
	 * @return Constrains[]
	 */
	protected function constraints()
	{
		return $this->dbField->filterConstraints();
	}

	protected function options()
	{
		return [
			'url' => $this->table->alias()
		];
	}


}