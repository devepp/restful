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
		$clone = clone $this;
		$clone->fields[] = $field;
		return $clone;
	}

	public function addFieldFromTable(Table $table, DatabaseField $dbField, $label)
	{
		$clone = clone $this;
		$clone->fields[] = new ReportField($table, $dbField, $label);
		return $clone;
	}

	public function addDefaultFieldsFromTable(Table $table)
	{
		$clone = clone $this;
		foreach ($table->getReportFields() as $field) {
			$clone->fields[] = $field;
		}
		return $clone;
	}

	public function addReportFilter(ReportFilterInterface $filter)
	{
		$clone = clone $this;
		$clone->filters[] = $filter;
		return $clone;
	}

	public function addFilterFromTable(Table $table, DatabaseField $dbField, $label)
	{
		$clone = clone $this;
		$clone->fields[] = new Filter($table, $dbField, $label);
		return $clone;
	}

	public function addDefaultFiltersFromTable(Table $table)
	{
		$clone = clone $this;
		foreach ($table->getReportFilters() as $filter) {
			$clone->filters[] = $filter;
		}
		return $clone;
	}

	public function excludeFieldFromTable(Table $table, DatabaseField $dbField, $label)
	{
		$field = new ReportField($table, $dbField, $label);

		$clone = clone $this;
		for ($i = 0; $i < count($clone->fields); $i++) {
			$currentField = $clone->fields[$i];
			if ($field->name() === $currentField->name()) {
				unset($clone->fields[$i]);
			}
		}

		return $clone;
	}

	public function excludeFilterFromTable(Table $table, DatabaseField $dbField, $label)
	{
		$filter = new Filter($table, $dbField, $label);

		$clone = clone $this;
		for ($i = 0; $i < count($clone->filters); $i++) {
			$currentFilter = $clone->filters[$i];
			if ($filter->name() === $currentFilter->name()) {
				unset($clone->filters[$i]);
			}
		}

		return $clone;
	}

	public function copyResource(Resource $resource)
	{
		$clone  = clone $this;
		foreach ($resource->availableFields() as $field) {
			$clone->fields[] = $field;
		}
		return $clone;
	}
}