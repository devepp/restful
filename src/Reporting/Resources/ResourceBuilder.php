<?php

namespace App\Reporting\Resources;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Filters\Filter;
use App\Reporting\ReportField;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;

class ResourceBuilder
{
	/** @var Table */
	private $table;
	/** @var string */
	private $name;

	/** @var ReportField[] */
	private $fields = [];

	/** @var Filter[] */
	private $filters = [];

	/**
	 * ResourceBuilder constructor.
	 * @param Table $table
	 * @param string $name
	 */
	public function __construct(Table $table, string $name)
	{
		$this->table = $table;
		$this->name = $name;
	}

	public function build()
	{
		return new Resource($this->table, $this->name, $this->fields, $this->filters);
	}

	public function addReportField(ReportFieldInterface $field)
	{
		$this->fields[] = $field;
	}

	public function addFieldFromTable(Table $table, DatabaseField $dbField, $label)
	{
		$this->fields[] = new ReportField($table, $dbField, $label);
	}

	public function addDefaultFieldsFromTable(Table $table)
	{
		foreach ($table->getReportFields() as $field) {
			$this->fields[] = $field;
		}
	}

	public function addReportFilter(ReportFilterInterface $filter)
	{
		$this->filters[] = $filter;
	}

	public function addFilterFromTable(Table $table, DatabaseField $dbField, $label)
	{
		$this->fields[] = new Filter($table, $dbField, $label);
	}

	public function addDefaultFiltersFromTable(Table $table)
	{
		foreach ($table->getReportFilters() as $filter) {
			$this->filters[] = $filter;
		}
	}
}